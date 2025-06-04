<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Atau sesuaikan dengan authorization logic Anda
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_promo' => 'required|string|max:255',
            'deskripsi_promo' => 'required|string|max:1000',
            'besaran_potongan' => 'required|integer|min:1|max:100',
            'minimum_belanja' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active' => 'sometimes|boolean',
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
            'nama_promo.required' => 'Nama promo harus diisi.',
            'nama_promo.string' => 'Nama promo harus berupa teks.',
            'nama_promo.max' => 'Nama promo maksimal 255 karakter.',
            
            'deskripsi_promo.required' => 'Deskripsi promo harus diisi.',
            'deskripsi_promo.string' => 'Deskripsi promo harus berupa teks.',
            'deskripsi_promo.max' => 'Deskripsi promo maksimal 1000 karakter.',
            
            'besaran_potongan.required' => 'Besaran potongan harus diisi.',
            'besaran_potongan.integer' => 'Besaran potongan harus berupa angka bulat.',
            'besaran_potongan.min' => 'Besaran potongan minimal 1%.',
            'besaran_potongan.max' => 'Besaran potongan maksimal 100%.',
            
            'minimum_belanja.required' => 'Minimum belanja harus diisi.',
            'minimum_belanja.numeric' => 'Minimum belanja harus berupa angka.',
            'minimum_belanja.min' => 'Minimum belanja tidak boleh negatif.',
            
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            
            'is_active.boolean' => 'Status aktif harus berupa true atau false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_promo' => 'nama promo',
            'deskripsi_promo' => 'deskripsi promo',
            'besaran_potongan' => 'besaran potongan',
            'minimum_belanja' => 'minimum belanja',
            'tanggal_mulai' => 'tanggal mulai',
            'tanggal_selesai' => 'tanggal selesai',
            'is_active' => 'status aktif',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation: Check if promo name is unique (case insensitive)
            $existingPromo = \App\Models\Promo::whereRaw('LOWER(nama_promo) = ?', [strtolower($this->nama_promo)])
                ->when($this->route('promo'), function ($query) {
                    return $query->where('id', '!=', $this->route('promo')->id);
                })
                ->first();

            if ($existingPromo) {
                $validator->errors()->add('nama_promo', 'Nama promo sudah digunakan.');
            }

            // Custom validation: Check date range doesn't conflict with other active promos
            if ($this->tanggal_mulai && $this->tanggal_selesai) {
                $conflictingPromo = \App\Models\Promo::where('is_active', true)
                    ->where(function ($query) {
                        $query->whereBetween('tanggal_mulai', [$this->tanggal_mulai, $this->tanggal_selesai])
                            ->orWhereBetween('tanggal_selesai', [$this->tanggal_mulai, $this->tanggal_selesai])
                            ->orWhere(function ($q) {
                                $q->where('tanggal_mulai', '<=', $this->tanggal_mulai)
                                  ->where('tanggal_selesai', '>=', $this->tanggal_selesai);
                            });
                    })
                    ->when($this->route('promo'), function ($query) {
                        return $query->where('id', '!=', $this->route('promo')->id);
                    })
                    ->first();

                if ($conflictingPromo) {
                    $validator->errors()->add('tanggal_mulai', 'Periode promo bertabrakan dengan promo lain yang aktif.');
                }
            }
        });
    }
}