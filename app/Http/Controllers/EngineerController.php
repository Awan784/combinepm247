<?php

namespace App\Http\Controllers;

use App\Events\OnNewUserCreation;
use App\Events\OnPasswordChange;
use App\Models\EngineerAvailability;
use App\Models\EngineerJobType;
use App\Models\EngineerModel;
use App\Models\JobType;
use App\Models\Job;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Services\DistanceService;
use Illuminate\Support\Facades\DB;

class EngineerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function uploadCSV(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        // Open the uploaded CSV file
        $file = fopen($request->file('file'), 'r');

        // Skip the header row (the first row)
        $header = fgetcsv($file);

        // Read each row of the CSV file
        while ($row = fgetcsv($file)) {
            // Get the data from the relevant columns
            $email = $row[2]; // Assuming 'email' is in the 3rd column (index 2)
            $phone = $row[5]; // Assuming 'phone' is in the 6th column (index 5)

            // Check if a user with the same email exists
            $user = User::where('email', $email)->first();

            if ($user) {
                // Update the phone number for existing user
                $user->update(['phone' => $phone]);
            } else {
                // Insert new user if the email does not exist
                User::create([
                    'id' => $row[0], // Assuming 'id' is the first column
                    'name' => $row[1], // Assuming 'name' is the second column
                    'email' => $row[2], // Email column
                    'email_verified_at' => $row[3], // Assuming email_verified_at is the 4th column
                    'password' => $row[4], // Assuming password is in the 5th column
                    'phone' => $row[5], // Phone column
                    'show_password' => $row[6], // Assuming show_password is the 7th column
                    'user_type_id' => $row[7], // Assuming user_type_id is the 8th column
                    'username' => $row[8], // Assuming username is the 9th column
                    'remember_token' => $row[9], // Assuming remember_token is the 10th column
                    'created_at' => $row[10], // Assuming created_at is the 11th column
                    'updated_at' => $row[11], // Assuming updated_at is the 12th column
                    'is_login' => $row[12], // Assuming is_login is the 13th column
                    'gmail_login' => $row[13], // Assuming gmail_login is the 14th column
                    'gmail_refresh_token' => $row[14], // Assuming gmail_refresh_token is the 15th column
                ]);
            }
        }

        // Close the file
        fclose($file);


       return 'CSV file processed successfully.';
    }
    
    public function index()
    {
        
        $engineers = EngineerModel::all();
        return view("engineers/index",compact('engineers'));
    }

    public function JobTypeEngineers(Request $request){
        $jobTypes = JobType::all();
        return view("engineers.job_type_engineers",compact('jobTypes'));
    }

    public function AvailableToday(Request $request)
    {
        $availabilities = EngineerAvailability::where("date_start",date("Y-m-d"))->get();
        return view("engineers.availablt_today",compact('availabilities'));
    }

    public function WeeklyAvailableEngineers(Request $request){
        $startDate = Carbon::now()->subDays(7)->startOfDay();
        // Retrieve EngineerAvailability records within the last 7 days
        $weeklyAvailable = EngineerAvailability::select(
            '*',
            DB::raw('TIMESTAMPDIFF(HOUR, start_time, end_time) as total_hours')
        )
        ->where('date_start', '>=', $startDate)
        ->orderBy('total_hours', 'desc')
        ->get();
        return view("engineers.weekly_available",compact('weeklyAvailable'));
    }

    public function SearchEngineer(Request $request)
    {
        $r_data = $request->all();
        //dd($r_data);
        if($request->has("post_codes"))
        {
            $search_code = $request->post_codes;
            $distanceService = new DistanceService();
            $post_codes = $distanceService->getRegionFromPostcode($search_code);

            $rating = $request->rating;
            $job_type_id = $request->job_type_id;
            $engineers = EngineerModel::whereHas('jobTypes', function ($query) use ($job_type_id) {
                $query->where('job_type_id', $job_type_id);
            })->whereHas("availability", function ($query) {
                $query->where("date_start", date("Y-m-d"));
            })->get();
            $filteredEngineers = $engineers->filter(function ($engineer) use ($post_codes) {
                $posts_array = explode(",", $engineer->postal_codes);
                return in_array($post_codes, $posts_array);
            });
            if ($rating) {
                $filteredEngineers = $filteredEngineers->where('rating', $rating);
            }
            $filteredEngineers = $filteredEngineers->sortBy('rating')->values();
            
            $engineers = new Collection($filteredEngineers->all());
            foreach ($engineers as $key => $engineer) {
                if ($engineer->lat !== null && $engineer->long !== null) {
                    $engineer['distance'] = $this->latlongDistance($search_code, $engineer->lat,$engineer->long);
                    $engineer['distance_type'] = "Mobile Latlong";
                    if ($engineer['distance'] == null) {
                        $engineer['distance'] = $this->postcodeDistance($search_code, $engineer->home_postcode);
                      //define for distance type
                    $engineer['distance_type'] = "Home Postcode";
                    }
                }else{
                    $engineer['distance'] = $this->postcodeDistance($search_code, $engineer->home_postcode);
                  //define for distance type
                    $engineer['distance_type'] = "Home Postcode";
                }
            }
            $postcode = $post_codes;
        }else{
            $engineers = EngineerModel::orderBy("rating","desc")->get();
            $postcode = "";
        }

        $jobtypes = JobType::all();
        $jobtype = null;
        if($request->has("job_type_id"))
        {
            $jobtype = JobType::find($request->job_type_id);
        }
        return view("engineers.search",compact('engineers','jobtypes','r_data','jobtype','postcode'));
    }
  
  
  public function jobSearch(Request $request, $jobId)
{
    $r_data = $request->all();
    $job = Job::find($jobId);
    $job_type_name = $job->job_type ?? null; // Retrieve job type name
    $postcode = $job->postcode ?? null; // Retrieve job postcode

    if ($job) {
        $search_code = $job->postcode;
        $distanceService = new DistanceService();
        $post_codes = $distanceService->getRegionFromPostcode($search_code);

        $rating = $request->rating;
        $job_type = JobType::where('title', $job_type_name)->first();
        $job_type_id = $job_type ? $job_type->id : null;

        $engineers = EngineerModel::whereHas('jobTypes', function ($query) use ($job_type_id) {
            $query->where('job_type_id', $job_type_id);
        })->whereHas("availability", function ($query) {
            $query->where("date_start", date("Y-m-d"));
        })->get();

        // Filter engineers by postcode
        $filteredEngineers = $engineers->filter(function ($engineer) use ($post_codes) {
            $posts_array = explode(",", $engineer->postal_codes);
            return in_array($post_codes, $posts_array);
        });

        // Filter by rating if provided
        if ($rating) {
            $filteredEngineers = $filteredEngineers->where('rating', $rating);
        }

        // Calculate and add distance for each engineer
        $filteredEngineers->each(function ($engineer) use ($search_code) {
            if ($engineer->lat !== null && $engineer->long !== null) {
                $engineer['distance'] = $this->latlongDistance($search_code, $engineer->lat, $engineer->long);
                $engineer['distance_type'] = "Mobile Latlong";
                if ($engineer['distance'] === null) {
                    $engineer['distance'] = $this->postcodeDistance($search_code, $engineer->home_postcode);
                    //define for distance type
                    $engineer['distance_type'] = "Home Postcode";
                }
            } else {
                $engineer['distance'] = $this->postcodeDistance($search_code, $engineer->home_postcode);
                //define for distance type
                $engineer['distance_type'] = "Home Postcode";
                
            }
        });

        // Sort engineers by distance in ascending order and by rating
        $filteredEngineers = $filteredEngineers->sortBy('distance')->sortByDesc('rating')->values();
        $engineers = new Collection($filteredEngineers->all());
        $postcode = $post_codes;
    } else {
        $engineers = EngineerModel::orderBy("rating", "desc")->get();
        $postcode = "";
    }

    $jobtypes = JobType::all();
    $jobtype = $job_type_id ? JobType::find($job_type_id) : null;

    return view("engineers.search_job", compact('engineers', 'jobtypes', 'r_data', 'jobtype', 'postcode'));
}

     public function jobSearchOLD(Request $request , $jobId)
    {
        $r_data = $request->all();
       
        $job = Job::find($jobId);
        $job_type_name = $job->job_type; // Retrieve job type name
        $postcode = $job->postcode; // Retrieve job postcode

       
        if($job)
        {
            $search_code = $job->postcode;;
            $distanceService = new DistanceService();
            $post_codes = $distanceService->getRegionFromPostcode($search_code);

           $rating = $request->rating;
           $job_type = JobType::where('title', $job_type_name)->first();
           $job_type_id = $job_type ? $job_type->id : null;

           // $job_type_id = $request->job_type_id;
            $engineers = EngineerModel::whereHas('jobTypes', function ($query) use ($job_type_id) {
                $query->where('job_type_id', $job_type_id);
            })->whereHas("availability", function ($query) {
                $query->where("date_start", date("Y-m-d"));
            })->get();
            $filteredEngineers = $engineers->filter(function ($engineer) use ($post_codes) {
                $posts_array = explode(",", $engineer->postal_codes);
                return in_array($post_codes, $posts_array);
            });
            if ($rating) {
                $filteredEngineers = $filteredEngineers->where('rating', $rating);
            }
            $filteredEngineers = $filteredEngineers->sortBy('rating')->values();
            
            $engineers = new Collection($filteredEngineers->all());
            foreach ($engineers as $key => $engineer) {
                if ($engineer->lat !== null && $engineer->long !== null) {
                    $engineer['distance'] = $this->latlongDistance($search_code, $engineer->lat,$engineer->long);
                 // dd($engineer['distance']);
                    if ($engineer['distance'] == null) {
                        $engineer['distance'] = $this->postcodeDistance($search_code, $engineer->home_postcode);
                    }
                }else{
                    $engineer['distance'] = $this->postcodeDistance($search_code, $engineer->home_postcode);
                }
            }
            $postcode = $post_codes;
        }else{
            $engineers = EngineerModel::orderBy("rating","desc")->get();
            $postcode = "";
        }

        $jobtypes = JobType::all();
        $jobtype = null;
        if($job_type_id)

        {
            $jobtype = JobType::find($job_type_id);
        }
       
     //  dd($jobtype);
        return view("engineers.search_job",compact('engineers','jobtypes','r_data','jobtype','postcode'));
    }
  
    

  public function jobSearch1(Request $request, $jobId)
{
    $r_data = $request->all(); // Retrieve all request data
    $engineers = collect(); // Initialize an empty collection for engineers

    // Find the job by ID to retrieve job type and postcode
    $job = Job::find($jobId);

    // Check if the job exists
    if ($job) {
        $job_type_name = $job->job_type; // Retrieve job type name
        $postcode = $job->postcode; // Retrieve job postcode

        // Retrieve the job type ID based on the job type name
        $job_type = JobType::where('title', $job_type_name)->first();
        $job_type_id = $job_type ? $job_type->id : null; // Get job type ID if available


            $distanceService = new DistanceService();
            $post_codes = $distanceService->getRegionFromPostcode($search_code);
        // Fetch engineers matching job type and availability today
        $engineers = EngineerModel::whereHas('jobTypes', function ($query) use ($job_type_id) {
            $query->where('job_type_id', $job_type_id);
        })->whereHas("availability", function ($query) {
            $query->where("date_start", date("Y-m-d")); // Filter by today's date
        })->get();

        // Filter engineers based on their postal codes matching the job's postcode
        $filteredEngineers = $engineers->filter(function ($engineer) use ($postcode) {
            $posts_array = explode(",", $engineer->postal_codes);
            return in_array($postcode, $posts_array); // Match engineer's postal codes
        });

        // Sort engineers by rating
        $filteredEngineers = $filteredEngineers->sortBy('rating')->values();

        // Calculate distance for each engineer
        foreach ($filteredEngineers as $engineer) {
            if ($engineer->lat !== null && $engineer->long !== null) {
                // Calculate distance using lat/long
                $engineer['distance'] = $this->latlongDistance($postcode, $engineer->lat, $engineer->long);
                if ($engineer['distance'] === null) {
                    $engineer['distance'] = $this->postcodeDistance($postcode, $engineer->home_postcode);
                }
            } else {
                // Fallback distance calculation based on postcode
                $engineer['distance'] = $this->postcodeDistance($postcode, $engineer->home_postcode);
            }
        }
        
        // Update engineers collection with filtered and sorted engineers
        $engineers = new Collection($filteredEngineers->all());
    } else {
        // If no job is found, retrieve all engineers ordered by rating
        $engineers = EngineerModel::orderBy("rating", "desc")->get();
        $postcode = ""; // Reset postcode
    }

    // Retrieve all job types
    $jobtypes = JobType::all();
    $jobtype = $job_type ?? null;

    // Return the job search view with the relevant data
    return view("engineers.search_job", compact('engineers', 'jobtypes', 'r_data', 'jobtype', 'postcode'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("engineers/create");
    }

    public function showAvailabilityForm(Request $request,$id)
    {
        $engineer = EngineerModel::find($id);
        $jobtypes = JobType::all();
        return view("engineers.availability",compact('engineer','jobtypes'));
    }

    public function AddAvailability(Request $request)
    {
        $data = $request->all();
        if(isset($data["avail_method"]) && $data["avail_method"] == "update")
        {
            $availability =  EngineerAvailability::find($data["update_id"]);

        }else{
            $availability = new EngineerAvailability($data);
            if ($request->monthAvailability) {
                $availability->fill($data);
                $availability->save();
                for ($i=0; $i < 29; $i++) { 
                    $data['date_start'] = Carbon::parse($data['date_start'])->addDay();
                    $availability = new EngineerAvailability();
                    $availability->fill($data);
                    $availability->save();
                }
            }
        }

        $availability->fill($data);
        $availability->save();
        return back()->with("success","Engineer Availability Date Data Updated");
    }


    public function updateAvailabilityValue(Request $request,$id)
    {

        $engineer = EngineerModel::find($id);
        if($request->has("rating")){
            $engineer->rating = $request->rating;
        }
        if($request->has("home_postcode")){
            $engineer->home_postcode = $request->home_postcode;
        }
        $engineer->postal_codes = implode(",",$request->postcodes);
        $engineer->save();
        $jobTypes = $engineer->jobTypes;
        //deleting previously entered jobtypes
        foreach($jobTypes as $type)
        {
            $type->delete();
        }

        foreach($request->jobtypes as $type)
        {
            EngineerJobType::create([
                "engineer_id" => $engineer->id,
                "job_type_id" => $type
            ]);
        }

        return back()->with("success","Job and Location details updated");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $engineer = new EngineerModel;
        $engineer->fill($data);
        $engineer->save();
        $password = $data["password"];
        $data["password"] = Hash::make($data["password"]);
        unset($data["cpassword"]);
        $user = new User();
        $user->fill($data);
        $user->user_type_id = UserType::ENGINEER;
        $user->save();
        $engineer->user_id = $user->id;
        $engineer->save();
        $data["password"] = $password;
        // event(new OnNewUserCreation($data));
        $html = view("mails.welcome",compact('data'))->render();
        $this->messageBirdEmail($data["email"], $html, "Welcome Pm247");
        return redirect("engineers")->with("success","Engineer Saved Successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(EngineerModel $engineerModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $engineer = EngineerModel::find($id);
        return view("engineers.edit",compact('engineer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $engineer = EngineerModel::find($id);
        $data = $request->all();
        $engineer->fill($data);
        $engineer->save();
        $user = $engineer->user;
        $isPasswordChanged = null;
        if(isset($data["password"]) && !empty($data["password"]))
        {
            $isPasswordChanged = $data["password"];//adding copy of password to receive in email
            $data["password"] = Hash::make($data["password"]);

        }else{
            unset($data["password"]);
        }
        $user->fill($data);
        $user->save();
        if($isPasswordChanged)
        {
            $data["password"] = $isPasswordChanged;
            $data["name"] = $engineer->name;
            // event(new OnPasswordChange($data));
            $html = view("mails.passwordChanged",compact('data'))->render();
            $this->messageBirdEmail($data["email"], $html, "Your password has been changed");
        }
        return redirect("engineers")->with("success","Engineer Updated Successfully");
    }

    public function RemoveEngineerAvailability(Request $request,$id)
    {
        $availability = EngineerAvailability::find($id);
        $availability->delete();
        return back()->with("error","Schedule Removed Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $engineer = EngineerModel::find($id);
        foreach($engineer->jobTypes as $type){
            $type->delete();
        }

        foreach($engineer->availability as $available){
            $available->delete();
        }

        $user_id = $engineer->user_id;
        $engineer->delete();
        $user = User::find($user_id);
        $user->delete();
        return back()->with("error","Engineer Removed Successfully");
    }

    public function ResetPassword(Request $request,$email)
    {
        $hashEmail = $email;
        $user = User::whereRaw('MD5(email) = ?', [$hashEmail])->first();
        if ($user) {
            return view('reset_password');
        }else{
            return redirect("/")->with("error","Email is not correct");
        }
    }
    public function ResetPasswordPost(Request $request,$email)
    {
        $hashEmail = $email;
        $user = User::whereRaw('MD5(email) = ?', [$hashEmail])->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect("/");
        }else{
            return redirect("/")->with("error","Email is not correct");
        }
    }
}
