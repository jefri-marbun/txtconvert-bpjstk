<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExcelController extends Controller
{
    public function showConverter()
    {
        return view('convert');
    }

        public function convertToTxt(Request $request)
    {
        $clientIp = $request->getClientIp();

        // \Log::info('Converter accessed by IP: ' . $clientIp);
        // $request->validate([
        //     'excelFile' => 'required|mimes:xls,xlsx',
        // ]);

        $request->validate([
            'excelFile' => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('excelFile');
        $originalFileName = $file->getClientOriginalName();

        try {
        
            \Log::info("B12:IP ADDRESS " . $request->getClientIp() . " B34:Name of the file " . $originalFileName . " B35: status success to convert");
        } catch (\Exception $e) {
        
            \Log::error("B12:IP ADDRESS " . $request->getClientIp() . " B34:Name of the file " . $originalFileName . " B35: status failed to convert. Error: " . $e->getMessage());
            
        }

        $file = $request->file('excelFile');
        $originalFileName = $file->getClientOriginalName();

        $filePath = $file->storeAs('uploads', $originalFileName, 'public');

        $data = Excel::toArray([], storage_path("app/public/uploads/{$originalFileName}"), null, null, false, false, 2);

        $txtFilePath = storage_path("app/public/uploads/{$originalFileName}.txt");

        $handle = fopen($txtFilePath, 'w');

        fputs($handle, "TGL SETTLE | DATE TRX | NO REFF/TRX ID | NO VIRTUAL BPJSKS | NAMA PELANGGAN | KODE CABANG BPJS | AMOUNT | JUMLAH ANGGOTA KELUARGA | KODE CA |\n");

        foreach ($data[0] as $row) {
            
            if (count($row) < 17) {
                continue;
            }

            $dateValue = $row[0] ?? '';

        
            try {
                $dateTime = Carbon::parse($dateValue);
                $tglSettle = $dateTime->format('Ymd');
                $dateTrx = $dateTime->format('Ymd H:i:s');
            } catch (\Exception $e) {
            
                continue; 
            }

            $noReffTrxId = $row[13] ?? '';
            $noVirtualBpjsks = $row[9] ?? '';
            if (empty($noVirtualBpjsks)) {
                continue;
            }
            $namaPelanggan = $row[4] ?? '';
            $kodeCabangBpjs = $row[12] ?? '';
            $amount = str_replace(',', '', $row[16] ?? ''); 
            $jumlahAnggotaKeluarga = $row[15] ?? '';
            $kodeCa = $row[14] ?? '';

            fputs($handle, "$tglSettle|$dateTrx|$noReffTrxId|$noVirtualBpjsks|$namaPelanggan|$kodeCabangBpjs|$amount|$jumlahAnggotaKeluarga|$kodeCa\n");
        }

        fclose($handle);

        $headers = [
            'Content-Type' => 'text/plain',
        ];

        return response()->download($txtFilePath, "{$originalFileName}.txt", $headers);
    }

    public function convert_bpjstk(Request $request)
{
    $request->validate([
        'excelFile' => 'required|mimes:xls,xlsx',
    ]);

    $file = $request->file('excelFile');
    $originalFileName = $file->getClientOriginalName();

    try {
        \Log::info("B12:IP ADDRESS " . $request->getClientIp() . " B34:Name of the file " . $originalFileName . " B35: status success to convert");
    } catch (\Exception $e) {
        \Log::error("B12:IP ADDRESS " . $request->getClientIp() . " B34:Name of the file " . $originalFileName . " B35: status failed to convert. Error: " . $e->getMessage());
    }

    $filePath = $file->storeAs('uploads', $originalFileName, 'public');

    $data = Excel::toArray([], storage_path("app/public/uploads/{$originalFileName}"), null, null, false, false, 5);

    $txtFilePath = storage_path("app/public/uploads/text_bpjstk{$originalFileName}.txt");

    $handle = fopen($txtFilePath, 'w');

    fputs($handle, "No REFF;Nomor ID;Kode Iuran;Kode Program;DateTime Trx;TotalAmount;Nama Customer;KodeCa;amount JHT;amount JKK;amount JKM;periode tagihan\n");

    foreach ($data[0] as $row) {
        $tanggal = $row[0] ?? '';
        $channel = $row[1] ?? '';
        $cif = $row[2] ?? '';
        $username = $row[3] ?? '';
        $nama = $row[4] ?? '';
        $amountJHT = $row[5] ?? '';
        $amountJKK = $row[6] ?? '';
        $amountJKM = $row[7] ?? '';
        $periodeTagihan = $row[8] ?? '';
        $kodeProgram = $row[9] ?? '';
        $kodeIuran = $row[10] ?? '';
        $cabang = $row[11] ?? '';
        $noReferensi = $row[12] ?? '';
        $noRekening = $row[13] ?? '';
        $transaksi = $row[14] ?? '';
        $noTujuan = $row[15] ?? '';
        $namaTujuan = $row[16] ?? '';
        $bankBillerTujuan = $row[17] ?? '';
        $transactionID = $row[18] ?? '';
        $nominal = $row[19] ?? '';
        $fee = $row[20] ?? '';
        $status = $row[21] ?? '';
        $keteranganGagal = $row[22] ?? '';

        try {
            $dateTime = Carbon::parse($tanggal);
            $dateTimeTrx = $dateTime->format('d-m-Y H:i:s');
        } catch (\Exception $e) {
            continue; // Skip row yang gagal parsing
        }

        // total amount
        $amountJHT = floatval(str_replace(',', '', $row[5] ?? ''));
        $amountJKK = floatval(str_replace(',', '', $row[6] ?? ''));
        $amountJKM = floatval(str_replace(',', '', $row[7] ?? ''));

        $dateTimeTrx = Carbon::parse($row[0])->format('Ymd H:i:s');

        if (empty($row[13]) || empty($row[9]) || empty($row[0]) || empty($row[11]) || empty($row[4]) || empty($row[15])) {
            \Log::warning("Skipping line due to missing or invalid data: " . json_encode($row));
            continue;
        }

        $totalAmount = $amountJHT + $amountJKK + $amountJKM;

        fputs(
            $handle,
            "$noReferensi|$noTujuan|$noRekening|JHT=$amountJHT#JKK=$amountJKK#JKM=$amountJKM|$dateTimeTrx|$totalAmount|$nama|566|$amountJHT|$amountJKK|$amountJKM|$periodeTagihan\n"
        );
    }

    fclose($handle);

    $headers = [
        'Content-Type' => 'text/plain',
    ];

    return response()->download($txtFilePath, "{$originalFileName}.txt", $headers);
}

    
    
    

    

        public function logButtonClick(Request $request)
        {
            $buttonType = $request->input('buttonType');
            \Log::info("Button clicked: {$buttonType}");

            return response()->json(['message' => 'Button click logged successfully']);
        }



}
