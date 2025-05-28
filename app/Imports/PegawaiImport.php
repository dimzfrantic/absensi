<?php

namespace App\Imports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PegawaiImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $nama = trim($row['nama_pegawai'] ?? '');
        $nipRaw = trim($row['nip'] ?? '');
        $nip = preg_replace('/\D/', '', $nipRaw);

        if (empty($nip) || empty($nama)) {
            return null; // skip jika kosong
        }

        // Generate QR code
        $qrCodeName = $nip . '.png';
        $qrCodePath = storage_path('app/public/qrcodes/' . $qrCodeName);
        QrCode::format('png')
        ->size(200)
        ->margin(1)
        ->generate($nip, $qrCodePath);

        return new Pegawai([
            'nama' => $nama,
            'nip' => $nip,
            'qrcode' => $qrCodeName,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_pegawai' => 'required',
            '*.nip' => ['required', 'regex:/^\d+$/', 'unique:pegawais,nip'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nip.required' => 'NIP wajib diisi.',
            '*.nip.regex' => 'NIP harus berupa angka.',
            '*.nip.unique' => 'NIP sudah ada di database.',
            '*.nama_pegawai.required' => 'Nama wajib diisi.',
        ];
    }
}
