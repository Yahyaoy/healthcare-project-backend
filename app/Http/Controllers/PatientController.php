<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PatientController extends Controller
{

    public function getDoctors(Request $request)
    {
        $doctors = User::where('role', 'doctor')
            ->when($request->specialty, function ($query, $specialty) {
                return $query->where('specialty', $specialty);
            })
            ->get();
        return response()->json(['doctors' => $doctors]);
    }

    public function bookAppointment(Request $request, $doctor_id)
    {
        $request->validate([
            'start_time' => 'required|date_format:Y-m-d H:i', // صيغة الوقت بدون الثواني
        ]);

        // حساب وقت الانتهاء تلقائيً بعد نصف ساعة
        $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->start_time);
        $endTime = $startTime->copy()->addMinutes(30);

        // استخراج اليوم من التاريخ
        $dayOfWeek = $startTime->format('l'); // مثلاً Monday, Tuesday

        // التحقق مما إذا كان الوقت ضمن جدول الطبيب
        $isWithinSchedule = DoctorSchedule::where('doctor_id', $doctor_id)
            ->where('day', $dayOfWeek)
            ->where('start_time', '<=', $startTime->format('H:i'))
            ->where('end_time', '>=', $endTime->format('H:i'))
            ->exists();

        if (!$isWithinSchedule) {
            return response()->json([
                'message' => 'The selected time is outside the doctor\'s available schedule. Please choose another time.',
            ], 400);
        }

        // التحقق من وجود حجز متداخل لنفس الطبيب
        $isBooked = \App\Models\Appointment::where('doctor_id', $doctor_id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($isBooked) {
            return response()->json([
                'message' => 'This time slot is already booked. Please choose another time.',
            ], 400);
        }

        // إنشاء الحجز
        $appointment = \App\Models\Appointment::create([
            'doctor_id' => $doctor_id,
            'patient_id' => auth()->id(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Appointment request sent.',
            'appointment' => $appointment,
        ], 201);
    }


    public function getDoctorSchedule($doctor_id)
    {
        $doctor = User::where('id', $doctor_id)->where('role', 'doctor')->firstOrFail();

        $schedule = DoctorSchedule::where('doctor_id', $doctor_id)->get();

        return response()->json([
            'doctor' => [
                'name'=> $doctor->name,
                'specialty' => $doctor->specialty,
            ],
            'schedule' => $schedule,
        ]);
    }

    public function getBookedSlots(Request $request, $doctor_id)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d', // تاريخ اليوم المطلوب
        ]);

        // جلب الأوقات المحجوزة للطبيب في التاريخ المحدد
        $bookedSlots = \App\Models\Appointment::where('doctor_id', $doctor_id)
            ->whereDate('start_time', $request->date)
            ->where('status', 'approved') // عرض الأوقات المحجوزة فقط إذا كانت الحالة "مقبولة"
            ->get(['start_time', 'end_time'])
            ->map(function ($appointment) {
                return [
                    'start_time' => $appointment->start_time->format('H:i'),
                    'end_time' => $appointment->end_time->format('H:i'),
                ];
            });

        return response()->json([
            'message' => 'Booked slots retrieved successfully.',
            'booked_slots' => $bookedSlots,
        ]);
    }


}
