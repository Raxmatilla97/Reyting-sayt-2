<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Table1_1_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    public function fields()
{
    return [
        'daraja_bergan_otm_nomi' => '<input type="text" name="daraja_bergan_otm_nomi" placeholder="Daraja bergan OTM nomi">',
        'phd_diplom_seryasi' => '<input type="text" name="phd_diplom_seryasi" placeholder="PhD diplom seryasi">',
        'phd_diplom_raqami' => '<input type="text" name="phd_diplom_raqami" placeholder="PhD diplom raqami">',
        // Qo'shimcha maydonlar...
    ];
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
