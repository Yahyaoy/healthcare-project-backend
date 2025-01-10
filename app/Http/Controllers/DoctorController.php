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


    public function approveAppointment($id)
    {
        // Find the appointment
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'status'=> 'error',
                'message' =>'Appointment not found',
            ], 404);
        }

        // Check if the appointment is already approved or rejected
        if ($appointment->status ==='approved') {
            return response()->json([
                'status' =>'error',
                'message' =>'Appointment is already approved',
            ], 400);
        }

        if ($appointment->status ==='rejected') {
            return response()->json([
                'status' =>'error',
                'message'=> 'Appointment has already been rejected',
            ], 400);
        }

        // Update the appointment status to approved
        $appointment->status='approved';
        $appointment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment approved successfully',
            'appointment' => $appointment,
        ], 200);
    }

    public function rejectAppointment($id)
    {
        // Find  appointment
        $appointment =Appointment::find($id);

        if (!$appointment){
            return response()->json([
                'status' =>'error',
                'message'=> 'Appointment not found',
            ], 404);
        }

        // Check if the appointment is approved or rejected
        if ($appointment->status === 'approved') {
            return response()->json([
                'status' => 'error',
                'message' =>'Appointment is already approved and cannot be rejected',
            ], 400);
        }

        if ($appointment->status === 'rejected') {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment has already been rejected',
            ], 400);
        }

        // Update the appointment status to rejected
        $appointment->status = 'rejected';
        $appointment->save();

        return response()->json([
            'status'=> 'success',
            'message' =>'Appointment rejected successfully',
            'appointment' => $appointment,
        ], 200);
    }

}
