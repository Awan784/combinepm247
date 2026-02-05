<?php

namespace App\Http\Controllers;

use App\Events\OnNewUserCreation;
use App\Models\ModuleModel;
use App\Models\PermissionsModel;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Events\OnPasswordChange;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where("user_type_id",UserType::SYSTEMUSER)->get();

        return view("users.index",compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view("users.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'email' => 'required|email|unique:users',
        ]);
        $data = $request->all();
        $data["show_password"] = $data["password"];
        $data["password"] = Hash::make($data["password"]);
        $data["user_type_id"] = UserType::SYSTEMUSER;
        $data["name"] = $data["username"];
        $user = new User($data);
        $user->save();
        foreach(ModuleModel::all() as $module)
        {
            if(isset($data[$module->id . "permission"]))
            {
                foreach($data[$module->id . "permission"] as $permission)
                {
                    PermissionsModel::create([
                        "permission_id" => $permission,
                        "module_id" => $module->id,
                        "user_id" => $user->id
                    ]);
                }
            }
        }
        $data["password"] = $data["show_password"];
        // event(new OnNewUserCreation($data));
        $html = view("mails.welcome",compact('data'))->render();
        $this->messageBirdEmail($data["email"], $html, "Welcome Pm247");

        return redirect("users")->with("success","User Created Successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view("users.edit",compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $id,
        ]);
        $user = User::find($id);


        $data = $request->all();
        if(isset($data["password"]) && !empty($data["password"])){
            $data["show_password"] = $data["password"];
            $data["password"] = Hash::make($data["password"]);
        }else{
            unset($data["password"]);
        }
        $data["name"] = $data["username"];
        $user->fill($data);
        $user->save();
        foreach($user->permissions as $permission)
        {
            $permission->delete();
        }
        foreach(ModuleModel::all() as $module)
        {
            if(isset($data[$module->id . "permission"]))
            {
                foreach($data[$module->id . "permission"] as $permission)
                {
                    PermissionsModel::create([
                        "permission_id" => $permission,
                        "module_id" => $module->id,
                        "user_id" => $user->id
                    ]);
                }
            }
        }
        if(isset($data["password"]) && !empty($data["password"])){
            $data["password"] = $data["show_password"];
            // event(new OnPasswordChange($data));
        }else{
            $data['password'] = $user['show_password'];
        }
        $html = view("mails.editUser",compact('data'))->render();
        // $this->InfoBipMail($data["email"],$html,"Welcome Pm247");
        $this->messageBirdEmail($data["email"],$html,"Welcome Pm247");
        return redirect("users")->with("success","User Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            foreach($user->permissions as $permission)
            {
                $permission->delete();
            }
            foreach ($user->user_jobs as $job) {
                $job->delete();
            }
            $user->delete();
            return back()->with("error","User Removed successfully");
        }else {
            return back()->with('error', 'User not found');
        }
    }
    
    
    
    public function redirectToGoogle()
{
    dd("hello");
    return Socialite::driver('google')->redirect();
}

/**
 * Handle Google callback
 */
public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'user_type_id' => UserType::SYSTEMUSER,
            ]);
        }

        Auth::login($user);
        dd($user);

        return redirect('/dashboard'); // change if needed

    } catch (\Exception $e) {
        return redirect('/login')->withErrors('Google login failed');
    }
}
}
