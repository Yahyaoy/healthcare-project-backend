<?php

namespace Database\Seeders;

use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch doctors from the database
        $doctors = User::where('role', 'doctor')->get();

        // Define the schedule for each doctor
        $schedules = [
            [
                'day' => 'Monday',
                'start_time' => '09:00',
                'end_time' => '13:00',
            ],
            [
                'day' => 'Wednesday',
                'start_time' => '10:00',
                'end_time' => '14:00',
            ],
            [
                'day' => 'Thursday',
                'start_time' => '12:00',
                'end_time' => '16:00',
            ],
        ];

        foreach ($doctors as $doctor) {
            foreach ($schedules as $schedule) {
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day' => $schedule['day'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                ]);
            }
        }
    }
}
