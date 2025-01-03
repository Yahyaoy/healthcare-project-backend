<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'د. محمد علي',
                'email' => 'dr.mohammed@example.com',
                'username' => 'dr_mohammed',
                'specialty' => 'Dermatologist',
                'phone_number' => '0590001111',
                'address' => 'شارع فلسطين، غزة',
            ],
            [
                'name' => 'د. أحمد سعيد',
                'email' => 'dr.ahmed@example.com',
                'username' => 'dr_ahmed',
                'specialty' => 'Ophthalmologist',
                'phone_number' => '0590002222',
                'address' => 'شارع النصر، غزة',
            ],
            [
                'name' => 'د. عائشة حسن',
                'email' => 'dr.aisha@example.com',
                'username' => 'dr_aisha',
                'specialty' => 'Pediatrist',
                'phone_number' => '0590003333',
                'address' => 'شارع عمر المختار، غزة',
            ],
            [
                'name' => 'د. ياسمين عبد الله',
                'email' => 'dr.yasmeen@example.com',
                'username' => 'dr_yasmeen',
                'specialty' => 'Dermatologist',
                'phone_number' => '0590004444',
                'address' => 'شارع الوحدة، غزة',
            ],
            [
                'name' => 'د. خالد أحمد',
                'email' => 'dr.khaled@example.com',
                'username' => 'dr_khaled',
                'specialty' => 'Ophthalmologist',
                'phone_number' => '0590005555',
                'address' => 'شارع البحر، غزة',
            ],
            [
                'name' => 'د. مروة حسن',
                'email' => 'dr.marwa@example.com',
                'username' => 'dr_marwa',
                'specialty' => 'Pediatrist',
                'phone_number' => '0590006666',
                'address' => 'شارع الكرامة، غزة',
            ],
        ];

        foreach ($doctors as $doctor) {
            User::create(array_merge($doctor, [
                'role' => 'doctor',
                'is_verified' => true,
                'password' => Hash::make('password123'),
            ]));
        }

        $patients = [
            [
                'name' => 'عبد الله محمود',
                'email' => 'abdullah@example.com',
                'username' => 'abdullah',
                'national_id' => '123456789012',
                'health_insurance_number' => 'HIN123456',
                'age' => 30,
                'gender' => 'Male',
                'phone_number' => '0591000001',
                'address' => 'شارع الثورة، غزة',
            ],
            [
                'name' => 'فاطمة عمر',
                'email' => 'fatima@example.com',
                'username' => 'fatima',
                'national_id' => '987654321098',
                'health_insurance_number' => 'HIN987654',
                'age' => 25,
                'gender' => 'Female',
                'phone_number' => '0591000002',
                'address' => 'شارع القدس، غزة',
            ],
            [
                'name' => 'يحيى خالد',
                'email' => 'yahya@example.com',
                'username' => 'yahya',
                'national_id' => '456789123456',
                'health_insurance_number' => 'HIN456789',
                'age' => 35,
                'gender' => 'Male',
                'phone_number' => '0591000003',
                'address' => 'شارع الشهداء، غزة',
            ],
            [
                'name' => 'سارة يوسف',
                'email' => 'sarah@example.com',
                'username' => 'sarah',
                'national_id' => '654321987654',
                'health_insurance_number' => 'HIN654321',
                'age' => 28,
                'gender' => 'Female',
                'phone_number' => '0591000004',
                'address' => 'شارع الحرم، غزة',
            ],
            [
                'name' => 'عمر إبراهيم',
                'email' => 'omar@example.com',
                'username' => 'omar',
                'national_id' => '321654987321',
                'health_insurance_number' => 'HIN321654',
                'age' => 32,
                'gender' => 'Male',
                'phone_number' => '0591000005',
                'address' => 'شارع الزيتون، غزة',
            ],
            [
                'name' => 'ريم خالد',
                'email' => 'reem@example.com',
                'username' => 'reem',
                'national_id' => '789456123789',
                'health_insurance_number' => 'HIN789456',
                'age' => 26,
                'gender' => 'Female',
                'phone_number' => '0591000006',
                'address' => 'شارع التحرير، غزة',
            ],
        ];

        foreach ($patients as $patient) {
            User::create(array_merge($patient, [
                'role' => 'patient',
                'is_verified' => false,
                'password' => Hash::make('password123'),
            ]));
        }
    }
}
