<?php
// app/Http/Requests/UpdateKategoriRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKategoriRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Mengambil ID kategori dari route parameter
        $kategoriId = $this->route('kategori')->id;

        return [
            // Rule 'unique' akan mengabaikan record dengan ID kategori yang sedang diedit
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategoris', 'nama_kategori')->ignore($kategoriId),
            ],
            'deskripsi' => 'nullable|string',
            'gambar_kategori' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];
    }
    
    /**
     * Get the custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
         // Kita bisa menggunakan messages dari StoreKategoriRequest jika sama
        return (new StoreKategoriRequest())->messages();
    }
}