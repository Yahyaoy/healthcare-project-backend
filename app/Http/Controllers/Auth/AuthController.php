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
                'username' => 'required|string|max:255|unique:users',
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
                'username' => $validated['username'],
                'role' => 'patient',
                'national_id' => $validated['national_id'],
                'health_insurance_number' => $validated['health_insurance_number'],
                'age' => $validated['age'],
                'gender' => $validated['gender'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'is_verified' => false
            ]);

            if ($request->hasFile('identity_image')) { // Make check
                $path = $request->file('identity_image')->store('identity_images', 'public');
                $user->update(['identity_image' => $path]);
            }
            return response()->json(['message' => 'Patient registered successfully!', 'user' => $user], 201);

        }


    public function registerDoctor(Request $request)
    {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'specialty' => 'nullable|in:Dermatologist,Anesthesiologist,Ophthalmologist,Pediatrist,Nephrologist,Psychiatrist,Pathology', // تخصص الدكتور مثلا باطنة او اطفال او ..
                'phone_number' => 'nullable|string',
                'address' => 'nullable|string',
            ]);

        $user = User::create([
            'name' => $validated['name'],
            'email'=> $validated['email'],
            'username' => $validated['username'],
            'role' =>'doctor',
            'specialty' => $validated['specialty'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'is_verified' => false // غير مفعّل

        ]);

        return response()->json(['message' =>'Doctor registered successfully!', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        // Validate
        $request->validate([
            'username'=>'required|string',
            'password'=>'required|string',
        ]);

        // find the user by username
        $user= User::where('username',$request->username)->first();

        // Check if user exists  and password correct
        if (!$user||!Hash::check($request->password,$user->password)) {
            return response()->json(['message'=> 'Invalid credentials'], 401);
        }
        // Generate a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user'=>[
                'id'=> $user->id,
                'username'=> $user->username,
                'email'=>$user->email,
                'role'=> $user->role, // admin,patient,doctor
             ],
        ], 200);
    }
}
