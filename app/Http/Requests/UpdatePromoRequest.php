<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Untuk kasus ini, aturan validasi update sama dengan store.
        // Jika ada aturan unik, modifikasinya dilakukan di sini.
        return (new StorePromoRequest())->rules();
    }

    public function messages(): array
    {
        return (new StorePromoRequest())->messages();
    }
}