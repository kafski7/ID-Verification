<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'staff_id'      => 'PSC-001',
                'full_name'     => 'John Doe',
                'position'      => 'Senior Engineer',
                'job_grade'     => 'Grade 9',
                'department'    => 'Engineering',
                'phone'         => '+260971000001',
                'email'         => 'john.doe@psc.gov',
                'date_of_issue' => Carbon::today(),
                'card_expires'  => Carbon::today()->addYears(3),
                'status'        => 'ACTIVE',
            ],
            [
                'staff_id'      => 'PSC-002',
                'full_name'     => 'Jane Smith',
                'position'      => 'HR Officer',
                'job_grade'     => 'Grade 7',
                'department'    => 'Human Resources',
                'phone'         => '+260971000002',
                'email'         => 'jane.smith@psc.gov',
                'date_of_issue' => Carbon::today(),
                'card_expires'  => Carbon::today()->addYears(3),
                'status'        => 'ACTIVE',
            ],
            [
                'staff_id'      => 'PSC-003',
                'full_name'     => 'Robert Banda',
                'position'      => 'Finance Officer',
                'job_grade'     => 'Grade 6',
                'department'    => 'Finance',
                'phone'         => null,
                'email'         => 'robert.banda@psc.gov',
                'date_of_issue' => Carbon::today()->subYears(2),
                'card_expires'  => Carbon::today()->addYear(),
                'status'        => 'INACTIVE',
            ],
        ];

        foreach ($records as $data) {
            Staff::firstOrCreate(['staff_id' => $data['staff_id']], $data);
        }
    }
}
