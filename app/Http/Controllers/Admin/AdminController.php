<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
   // رح نعرض كل المستخدمين الي هما المرضى والاطباء مع الاداري
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json([
            'message' =>'All users get successfully.',
            'users'=> $users,
        ], 200);
    }
    // التحقق من المستخدم وتوليد كلمة المرور
    public function verifyUser(Request $request, $username)
    {
        $request->validate([
            'password' =>'required|string|min:8',
        ]);

        // البحث عن المستخدم باستخدام اسم المستخدم
        $user = User::where('username',$username)->firstOrFail();

        // تحديث بيانات المستخدم
        $user->update([
            'password' =>Hash::make($request->password),
            'is_verified'=> true,
            'admin_id'=> auth()->id(), //  تسجيل الإداري المسؤول
        ]);

        // إرسال بيانات تسجيل  الدخول الى البريد الإلكتروني
        Mail::raw(
            "Your account has been approved.\n\nUsername: {$user->username}\nPassword: {$request->password}",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Account Approved');
            }
        );
        return response()->json(['message' =>'User is verified successfully', 'user' =>$user], 200);
    }

    public function deleteUser($username)
    {
        $user =User::where('username',$username)->firstOrFail();

        $adminDelete = auth()->user()->name; // اسم الإداري اللي قام بالحذف
        $user->delete();

        return response()->json([
            'message' => "User deleted successfully by :  {$adminDelete}.",
        ], 200);
    }

// بدنا نعرض المستخدمين اللي ما تم التحقق منهم
    public function pendingUsers()
    {
        $users = User::where('is_verified',false)->get(); // is verified => false يعني مش متحقق عالانتظار

        return response()->json([
            'message' =>'Pending users get successfully.',
            'users' =>$users
        ], 200);
    }
}
