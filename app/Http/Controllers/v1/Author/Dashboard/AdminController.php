<?php

namespace App\Http\Controllers\v1\Author\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FilterTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use ImageTrait,FilterTrait;
    public function list(Request $request)
    {
        $properties = [
            'status' => '=',
            'role_id' => '=',
            'address' => 'like',
        ];
        $globalSearchField = 'search';
        $globalSearchColumns = ['first_name', 'last_name', 'email'];
        $userQuery = $this->filter(User::class,'created_at',$request,$properties,'desc' ,$globalSearchField,$globalSearchColumns);
        $users = $userQuery->paginate(10);
        return view('pages.users.user-management' , compact('users'));
    }

    public function add()
    {
        return view('pages.users.add-user');
    }

    public function store(Request $request)
    {
        $imagePath = $this->saveImage($request,'image','users');
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role,
            'status' => $request->status,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $imagePath,
        ]);
        return redirect()->route('admins.list')->with('status' , 'User Added Successfully');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('pages.users.edit-user' , compact('user'));
    }

    public function update(Request $request , $id)
    {
        $user = User::find($id);
        $imagePath = $this->updateImage($request,'image', 'users',$user->image);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role_id' => $request->role,
            'status' => $request->status,
            'phone' => $request->phone,
            'description' => $request->description ?? $user->description,
            'work_link' => $request->work_link ?? $user->work_link,
            'address' => $request->address,
            'image' => $imagePath,
        ]);
        return redirect()->route('admins.list')->with('status' , 'User Updated Successfully');
    }

    public function delete($id)
    {
        $user = User::find($id);
        $this->deleteImage($user->image);
        $user->delete();
        return redirect()->route('admins.list')->with('demo' , 'User Deleted Successfully');
    }

    public function user_profile()
    {
        return view('pages.users.user-profile');
    }

    public function update_admin_profile(Request $request)
    {
        $admin = Auth::user();
        $image = $this->updateImage($request,"image","users",$admin->image);
        $admin->first_name = $request->first_name ?? $admin->first_name;
        $admin->last_name = $request->last_name ?? $admin->last_name;
        $admin->email = $request->email ?? $admin->email;
        $admin->phone = $request->phone ?? $admin->phone;
        $admin->image = $image ?? $admin->image;
        $admin->update();
        return redirect()->route('admins.profile')->with('status' , 'User Updated Successfully');

    }
}
