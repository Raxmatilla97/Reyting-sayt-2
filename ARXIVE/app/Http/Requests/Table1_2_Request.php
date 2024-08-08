<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Table1_2_Request extends FormRequest
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
        'xorijiy_davlat_nomi' => '<input type="text" name="xorijiy_davlat_nomi" placeholder="Xorijiy davlat nomi">',
        'otm_nomi' => '<input type="text" name="otm_nomi" placeholder="OTM nomi">',
        'mutaxasisligi' => '<input type="text" name="mutaxasisligi" placeholder="Mutaxasisligi">',
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
