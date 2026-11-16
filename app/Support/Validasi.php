<?php

namespace App\Support;

class Validasi
{
    public static function pesan(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'unique' => ':attribute sudah digunakan.',
            'max.string' => ':attribute maksimal :max karakter.',
            'min.string' => ':attribute minimal :min karakter.',
            'max.file' => 'Ukuran berkas maksimal :max KB.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'before_or_equal' => ':attribute tidak boleh melebihi hari ini.',
            'in' => ':attribute tidak valid.',
            'integer' => ':attribute harus berupa angka.',
            'numeric' => ':attribute harus berupa angka.',
            'exists' => ':attribute tidak ditemukan.',
            'confirmed' => 'Konfirmasi password tidak sama.',
            'file' => 'Berkas tidak valid.',
            'alpha_dash' => ':attribute hanya boleh huruf, angka, strip, dan garis bawah.',
            'date_format' => ':attribute tidak sesuai format.',
        ];
    }
}
