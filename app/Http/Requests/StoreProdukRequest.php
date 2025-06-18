<?php
// app/Http/Requests/StoreProdukRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Izinkan semua user yang terotentikasi untuk membuat request ini.
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
            'nama_produk' => 'required|string|max:100',
            'deskripsi_produk' => 'required|string',
            'stok_produk' => 'required|integer|min:0',
            'harga_produk' => 'required|numeric|min:0',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'id_kategori' => 'required|exists:kategoris,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'id_kategori.required' => 'Anda harus memilih kategori untuk produk ini.',
            'id_kategori.exists' => 'Kategori yang dipilih tidak valid.',
            'harga_produk.numeric' => 'Harga harus berupa angka.',
            'stok_produk.integer' => 'Stok harus berupa bilangan bulat.',
        ];
    }
}