<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isHrAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'staff_id'      => ['required', 'string', 'max:50', 'unique:staff,staff_id'],
            'full_name'     => ['required', 'string', 'max:255'],
            'position'      => ['required', 'string', 'max:255'],
            'job_grade'     => ['required', 'string', 'max:50'],
            'department'    => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'email'         => ['nullable', 'email', 'max:255'],
            'photo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'date_of_issue' => ['required', 'date'],
            'card_expires'  => ['required', 'date', 'after:date_of_issue'],
            'status'        => ['required', 'in:ACTIVE,INACTIVE'],
        ];
    }
}
