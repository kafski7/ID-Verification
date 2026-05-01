<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isHrAdmin() ?? false;
    }

    public function rules(): array
    {
        $staffUuid = $this->route('staff')?->uuid;

        return [
            'staff_id'        => ['required', 'string', 'max:50', "unique:staff,staff_id,{$staffUuid},uuid"],
            'id_no'           => ['required', 'string', 'max:50', "unique:staff,id_no,{$staffUuid},uuid"],
            'full_name'       => ['required', 'string', 'max:255'],
            'sex'             => ['required', 'in:M,F'],
            'position'        => ['required', 'string', 'max:255'],
            'job_grade'       => ['required', 'string', 'max:50'],
            'department'      => ['required', 'string', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:30'],
            'email'           => ['nullable', 'email', 'max:255'],
            'other_contacts'  => ['nullable', 'string', 'max:500'],
            'photo'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'date_of_issue'   => ['required', 'date'],
            'card_expires'    => ['required', 'date', 'after:date_of_issue'],
            'status'          => ['required', 'in:ACTIVE,INACTIVE'],
        ];
    }
}
