<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePesananRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
            'alamat_id' => 'nullable|exists:alamats,id',
            'alamat_pengiriman_custom' => 'nullable|string|max:500|required_without:alamat_id',
            'promo_id' => 'nullable|exists:promos,id'
        ];
    }
    
    public function messages(): array
    {
        return [
            'alamat_pengiriman_custom.required_without' => 'Anda harus memilih alamat tersimpan atau mengisi alamat pengiriman baru.'
        ];
    }
}