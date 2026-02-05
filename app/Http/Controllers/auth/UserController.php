<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use App\Models\EngineerModel;
use App\Models\EngineerAvailability;
use App\Models\JobType;
use App\Models\Job;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use Google\Service\Gmail;


class UserController extends Controller
{
    // dashboard functions
    public function index()
    {

        if(auth()->user())
        {
            return redirect('dashboard/assign');
        }else{
            return view('welcome');
        }
    }
    public function EngineerDashboard()
    {
        if(auth()->user()->user_type_id !== UserType::ENGINEER){
            $availabilities = EngineerAvailability::where("date_start",date("Y-m-d"))->limit(10)->get();
            $jobTypes = JobType::all();
            // Calculate the date 7 days ago
            $startDate = Carbon::now()->subDays(7)->startOfDay();
            // Retrieve EngineerAvailability records within the last 7 days
            $weeklyAvailable = EngineerAvailability::select(
                '*',
                DB::raw('TIMESTAMPDIFF(HOUR, start_time, end_time) as total_hours')
            )
            ->where('date_start', '>=', $startDate)
            ->orderBy('total_hours', 'desc')
            ->get();

            return view("engineers.dashboard",compact('availabilities','jobTypes','weeklyAvailable','weeklyAvailable'));
        }else{
            $user = auth()->user();
            $engineer = EngineerModel::where(["user_id" => $user->id])->first();
            $jobtypes = JobType::all();
            return view("engineers.availability",compact('engineer','jobtypes'));
        }

    }
    public function ContractDashboard()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        if(auth()->user()->user_type_id !== UserType::ENGINEER){
            $contracts = Contract::latest()->take(10)->get()->reject(function ($contract) use ($sevenDaysAgo) {
                            return $contract->job->created_at < $sevenDaysAgo;
                        });
            $payments = Payment::latest()->take(10)->get()->reject(function ($payment) use ($sevenDaysAgo) {
                            return $payment->job->created_at < $sevenDaysAgo;
                        });
            return view("jobs.dashboard",compact('contracts','payments'));
        }else{
            $user = auth()->user();
            $jobIds = Job::where('engineer_id',$user->id)->pluck('id');
            $contracts = Contract::whereIn('job_id',$jobIds)->latest()->take(10)->get()->reject(function ($contract) use ($sevenDaysAgo) {
                            return $contract->job->created_at < $sevenDaysAgo;
                        });
            $payments = Payment::whereIn('job_id',$jobIds)->latest()->take(10)->get()->reject(function ($payment) use ($sevenDaysAgo) {
                            return $payment->job->created_at < $sevenDaysAgo;
                        });
            return view("engineers.job_dashboard",compact('contracts','payments'));
        }
    }
    public function AssignDashboard()
    {
        if(auth()->user()->user_type_id !== UserType::ENGINEER){
            $currentDate = Carbon::now()->toDateString();
            $user = auth()->user();
            $job = Job::visibleToUser($user)
                ->where('created_at', '>=', Carbon::now()->subDays(3))
                ->where('date', '<' , $currentDate)
                ->where('status', 'Active')
                ->orderBy('date', 'asc')
                ->get();
            $job1 = Job::visibleToUser($user)
                ->where('created_at', '>=', Carbon::now()->subDays(3))
                ->where('date', $currentDate)
                ->where('status', 'Active')
                ->orderBy('date', 'asc')
                ->get();
            $job2 = Job::visibleToUser($user)
                ->where('created_at', '>=', Carbon::now()->subDays(3))
                ->where('date', '>' , $currentDate)
                ->where('status', 'Active')
                ->orderBy('date', 'asc')
                ->get();
            $job3 = Job::visibleToUser($user)
                ->where('created_at', '>=', Carbon::now()->subDays(3))
                ->where('status', 'Completed')
                ->orderBy('date', 'asc')
                ->get();
            return view("jobs.index",compact('job', 'job1', 'job2', 'job3'));
        }else{
            return redirect('dashboard/engineer');
        }
    }

    // tv functions
    public function EngineerTv(Request $request){
        $jobTypes = JobType::all();
        // Calculate the date 7 days ago
        $startDate = Carbon::now()->subDays(7)->startOfDay();
        // Retrieve EngineerAvailability records within the last 7 days
        $weeklyAvailable = EngineerAvailability::select(
            '*',
            DB::raw('TIMESTAMPDIFF(HOUR, start_time, end_time) as total_hours')
        )
        ->where('date_start', '>=', $startDate)
        ->orderBy('total_hours', 'desc')
        ->get();
        return view("tv.engineer",compact('jobTypes','weeklyAvailable','weeklyAvailable'));
    }
    public function getAvailableEngineers(Request $request)
    {
        $availableEngineer = EngineerAvailability::select('engineer_id', DB::raw('MIN(id) as id'), DB::raw('MIN(date_start) as date_start'), DB::raw('MIN(end_time) as end_time'))
            ->where("date_start", date("Y-m-d"))
            ->where("end_time", ">", date("H:i:s"))
            ->groupBy("engineer_id")
            ->with("engineer", "engineer.jobTypes.jobtype")
            ->get();
        return $availableEngineer;
    }
    public function ContractTv(Request $request){
        $jobs = Job::where('created_at', '>=', Carbon::now()->subDays(7))->where('contract_status', '1')->latest()->get();
        return view("tv.contract",compact('jobs'));
    }
    public function AssignTv(Request $request){
        $currentDate = Carbon::now()->toDateString();
        $jobs = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('date', $currentDate)->where('status','active')->get();
        return view("tv.assign",compact('jobs'));
    }





    // other functions
    public function logout(Request $request){
        Auth::logout();
        return redirect("/");
    }

    public function processAuthRequest(Request $request)
    {

        $data = $request->all();
        unset($data["_token"]);
        if(Auth::attempt($data))
        {
            return redirect("/");
        }else{
            return back()->with("error","Wrong email or password");
        }
    }

    public function redirectToGoogleOld()
    {
        $client = new GoogleClient();
        $client->setClientId(config('gmailApiEnv.GOOGLE_CLIENT_ID'));
        $client->setClientSecret(config('gmailApiEnv.GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(config('gmailApiEnv.GOOGLE_REDIRECT_URI'));
        $client->addScope('https://www.googleapis.com/auth/gmail.readonly');
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }
    
    public function redirectToGoogle()
{
    $client = new GoogleClient();
    $client->setClientId(config('gmailApiEnv.GOOGLE_CLIENT_ID'));
    $client->setClientSecret(config('gmailApiEnv.GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(config('gmailApiEnv.GOOGLE_REDIRECT_URI'));
    $client->addScope(Gmail::GMAIL_READONLY);
    $client->setAccessType('offline');
    $client->setPrompt('consent');

    return redirect()->away($client->createAuthUrl());
}

public function handleGoogleCallback(Request $request)
{
    if (!$request->has('code')) {
        return redirect()->route('login.google');
    }

    $client = new GoogleClient();
    $client->setClientId(config('gmailApiEnv.GOOGLE_CLIENT_ID'));
    $client->setClientSecret(config('gmailApiEnv.GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(config('gmailApiEnv.GOOGLE_REDIRECT_URI'));

    $token = $client->fetchAccessTokenWithAuthCode($request->code);

    if (isset($token['error'])) {
        return redirect('/dashboard/assign')->with('gmail_error', $token['error_description']);
    }

    $client->setAccessToken($token);

    $service = new Gmail($client);
    $profile = $service->users->getProfile('me');

    if ($profile->getEmailAddress() !== 'mailpm247@gmail.com') {
        return redirect('/dashboard/assign')->with('gmail_wrong', 'Unauthorized Gmail account.');
    }

    // Save refresh token safely
    if (!empty($token['refresh_token'])) {
        auth()->user()->update([
            'gmail_refresh_token' => $token['refresh_token'],
            'gmail_login' => 1
        ]);
    }

    return redirect()->route('mails.check');
}
    public function handleGoogleCallbackOld(Request $request)
    {
        $client = new GoogleClient();
        $client->setClientId(config('gmailApiEnv.GOOGLE_CLIENT_ID'));
        $client->setClientSecret(config('gmailApiEnv.GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(config('gmailApiEnv.GOOGLE_REDIRECT_URI'));

        if ($request->has('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

            // restrict gmail must have agentspm247@gmail.com
            $client->setAccessToken($token);
            $service = new Gmail($client);
            $userProfile = $service->users->getProfile('me');
            $userEmail = $userProfile->getEmailAddress();

            if ($userEmail !== 'mailpm247@gmail.com') {
                return redirect('/dashboard/assign')->with('gmail_wrong', 'Unauthorized email address. You are not contacting with agentspm247@gmail.com.');
            }
            // Check if refresh token is present and save it
            if (isset($token['refresh_token'])) {
                $refreshToken = $token['refresh_token'];
                // Save the refresh token in the database for the authenticated user
                $updatedUser = User::find(auth()->user()->id);
                $updatedUser->gmail_refresh_token = $refreshToken;
                $updatedUser->save();
            } else {
                // Use the existing refresh token if no new one is provided
                $refreshToken = User::find(auth()->user()->id)->gmail_refresh_token;
                if (!$refreshToken) {
                    return redirect('/')->with('gmail_error', 'Failed to get a refresh token.');
                }
            }

            $request->session()->put('gmail_token', $token);
            $updatedUser = User::find(auth()->user()->id);
            $updatedUser->gmail_login = 1;
            $updatedUser->save();
            return redirect()->route('mails.check');
        }

        return redirect()->route('login.google');

    }

}
