<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromoRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        // Izinkan semua user yang sudah terotentikasi di panel admin.
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_promo' => 'required|string|max:255',
            'deskripsi_promo' => 'required|string',
            'besaran_potongan' => 'required|integer|min:1|max:100',
            'minimum_belanja' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ];
    }

    /**
     * Dapatkan pesan error kustom untuk aturan validasi.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_promo.required' => 'Nama promo wajib diisi.',
            'deskripsi_promo.required' => 'Deskripsi promo wajib diisi.',
            'besaran_potongan.required' => 'Besaran potongan (persentase) wajib diisi.',
            'besaran_potongan.min' => 'Besaran potongan minimal 1%.',
            'besaran_potongan.max' => 'Besaran potongan maksimal 100%.',
            'minimum_belanja.required' => 'Minimum belanja wajib diisi.',
            'minimum_belanja.min' => 'Minimum belanja tidak boleh negatif.',
            'tanggal_mulai.required' => 'Tanggal mulai promo wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai promo wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];
    }
}