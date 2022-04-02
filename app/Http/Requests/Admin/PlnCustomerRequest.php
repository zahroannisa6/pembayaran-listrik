<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlnCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_pelanggan' => 'required|string',
            'nomor_meter' => 'required|numeric|unique:pln_customers',
            'alamat' => 'required|string|nullable',
            'id_tarif' => 'required|numeric|exists:tariffs,id',
            'id_kota' => 'required|exists:indonesia_cities,id'
        ];
    }

    public function messages()
    {
        return [
            'nama_pelanggan.required' => 'Nama pelanggan tidak boleh kosong',
            'nomor_meter.required' => 'Nama pelanggan tidak boleh kosong',
            'alamat.required' => 'Nama pelanggan tidak boleh kosong',
            'id_tarif.required' => 'Tarif tidak boleh kosong',
            'nama_pelanggan.string' => 'Nama pelanggan harus berupa huruf abjad',
            'alamat.string' => 'Alamat harus berupa string',
            'nomor_meter.numeric' => 'Nomor meter harus berupa angka',
            'id_tarif.numeric' => 'ID tarif harus berupa angka',
            'id_tarif.exists' => 'Tarif tidak ada di dalam tabel',
            'nomor_meter.unique' => 'Nomor meter sudah dipakai',
            'id_kota.exists' => 'Kota tidak ada di dalam tabel',
        ];
    }
}
