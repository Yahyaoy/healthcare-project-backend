<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function registerPatient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'national_id' =>'required|string|unique:users' , // رقم الهوية ما بتكرر
            'health_insurance_number'=> 'nullable|string',
            'age' => 'nullable|integer' ,
            'gender' => 'required|in:male,female',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'identity_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'patient',
            'national_id' => $validated['national_id'],
            'health_insurance_number' => $validated['health_insurance_number'],
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
        ]);


        if ($request->hasFile('identity_image')) { // Make check
            $path = $request->file('identity_image')->store('identity_images', 'public');
            $user->update(['identity_image' => $path]);
        }
        dd(555);
        return response()->json(['message' => 'Patient registered successfully!', 'user' => $user], 201);

    }


    public function registerDoctor(Request $request)
    {
        $validated = $request->validate([
            'name ' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'specialty' => 'required|string', // تخصص الدكتور مثلا باطنة او اطفال او ..
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email'=> $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' =>'doctor',
            'specialty' => $validated['specialty'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
        ]);

        return response()->json(['message' =>'Doctor registered successfully!', 'user' => $user], 201);
    }


}
