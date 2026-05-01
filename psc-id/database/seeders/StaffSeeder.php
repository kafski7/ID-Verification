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
                'staff_id'       => 'PSC-001',
                'id_no'          => '100000001',
                'full_name'      => 'John Doe',
                'sex'            => 'M',
                'position'       => 'Senior Engineer',
                'job_grade'      => 'Grade 9',
                'department'     => 'Engineering',
                'phone'          => '+260971000001',
                'email'          => 'john.doe@psc.gov',
                'other_contacts' => null,
                'date_of_issue'  => Carbon::today(),
                'card_expires'   => Carbon::today()->addYears(3),
                'status'         => 'ACTIVE',
            ],
            [
                'staff_id'       => 'PSC-002',
                'id_no'          => '100000002',
                'full_name'      => 'Jane Smith',
                'sex'            => 'F',
                'position'       => 'HR Officer',
                'job_grade'      => 'Grade 7',
                'department'     => 'Human Resources',
                'phone'          => '+260971000002',
                'email'          => 'jane.smith@psc.gov',
                'other_contacts' => 'Alt: +260971000099',
                'date_of_issue'  => Carbon::today(),
                'card_expires'   => Carbon::today()->addYears(3),
                'status'         => 'ACTIVE',
            ],
            [
                'staff_id'       => 'PSC-003',
                'id_no'          => '100000003',
                'full_name'      => 'Robert Banda',
                'sex'            => 'M',
                'position'       => 'Finance Officer',
                'job_grade'      => 'Grade 6',
                'department'     => 'Finance',
                'phone'          => null,
                'email'          => 'robert.banda@psc.gov',
                'other_contacts' => null,
                'date_of_issue'  => Carbon::today()->subYears(2),
                'card_expires'   => Carbon::today()->addYear(),
                'status'         => 'INACTIVE',
            ],
        ];

        foreach ($records as $data) {
            Staff::firstOrCreate(['staff_id' => $data['staff_id']], $data);
        }
    }
}
