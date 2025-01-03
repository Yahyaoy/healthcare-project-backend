<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    //
    public function addSchedule(Request $request)
    {
        $request->validate([
            'day' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // حفظ الجدول في قاعدة البيانات
        $schedule = DoctorSchedule::create([
            'doctor_id' => auth()->id(), // الطبيب الذي يقوم بإضافة المواعيد
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json(['message' => 'Schedule added successfully.', 'schedule' => $schedule], 201);
    }
    public function getSchedule()
    {
        $schedule = DoctorSchedule::where('doctor_id',auth()->id())->get();
        return response()->json(['schedule' => $schedule]);

    }

    public function getPendingPatients()
    {
        $doctor = auth()->user();

        // Ensure the authenticated user is a doctor
        if ($doctor->role!=='doctor') {
            return response()->json([
                'message' => 'Unauthorized access.',
            ], 403);
        }

        // Fetch pending appointments for a doctor
        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->with('patient:id,name,email,phone_number')
            ->get();

        return response()->json([
            'message' => 'Pending patients retrieved successfully.',
            'pending_patients' => $pendingAppointments,
        ], 200);
    }




}
