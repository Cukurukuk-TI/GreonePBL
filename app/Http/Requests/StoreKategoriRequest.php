<?php
// app/Http/Requests/StoreKategoriRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set ke true agar request bisa diproses.
        // Anda bisa menambahkan logika otorisasi di sini nanti.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
            'deskripsi' => 'nullable|string',
            'gambar_kategori' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048' // Menambahkan mimes untuk keamanan
        ];
    }

    /**
     * Get the custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Gagal menyimpan, nama kategori ini sudah tersedia. Silakan gunakan nama lain.',
            'gambar_kategori.image' => 'File yang diunggah harus berupa gambar.',
            'gambar_kategori.max' => 'Ukuran gambar tidak boleh melebihi 2MB.',
        ];
    }
}