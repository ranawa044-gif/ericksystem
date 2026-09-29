<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ImportExcelController extends Controller
{
    public function downloadMahasiswaTemplate()
    {
        $path = $this->buildXlsx('template-import-mahasiswa.xlsx', [
            ['nim', 'nama', 'program_studi', 'angkatan', 'dosen_wali_nidn', 'email', 'password'],
            ['231001002', 'Siti Aminah', 'Sistem Informasi', '2023', '0012047801', '231001002@student.local', 'password'],
        ]);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function importMahasiswa(Request $request)
    {
        $this->ensurePengelola();

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx'],
        ]);

        $rows = $this->readXlsxRows($request->file('file')->getRealPath());
        $header = array_map(fn ($value) => strtolower(trim((string) $value)), array_shift($rows) ?? []);
        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $data = $this->mapRow($header, $row);

            if (empty($data['nim']) || empty($data['nama'])) {
                $skipped++;
                continue;
            }

            $dosen = null;

            if (! empty($data['dosen_wali_nidn'])) {
                $dosen = Dosen::query()->where('nidn', $data['dosen_wali_nidn'])->first();
            }

            $dosen ??= Dosen::query()->first();

            if (! $dosen) {
                $skipped++;
                continue;
            }

            $mahasiswa = Mahasiswa::updateOrCreate(
                ['nim' => $data['nim']],
                [
                    'nama' => $data['nama'],
                    'program_studi' => $data['program_studi'] ?: 'Sistem Informasi',
                    'angkatan' => (int) ($data['angkatan'] ?: now()->year),
                    'dosen_wali_id' => $dosen->id,
                ]
            );

            $email = $data['email'] ?: strtolower($data['nim']) . '@student.local';
            $password = $data['password'] ?: 'password';

            User::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id],
                [
                    'name' => $mahasiswa->nama,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'mahasiswa',
                    'dosen_id' => null,
                ]
            );

            $imported++;
        }

        return redirect()
            ->route('pengelola.mahasiswa')
            ->with('success', "Import mahasiswa selesai. Berhasil: {$imported}, dilewati: {$skipped}.");
    }

    public function downloadNilaiTemplate()
    {
        $path = $this->buildXlsx('template-import-nilai.xlsx', [
            ['nim', 'semester_urutan', 'tahun_akademik', 'periode', 'kode_mk', 'nilai_angka'],
            ['231001001', '1', '2023/2024', 'ganjil', 'SI101', '88'],
            ['231001001', '2', '2023/2024', 'genap', 'SI201', '85'],
        ]);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function importNilai(Request $request)
    {
        $this->ensurePengelola();

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx'],
        ]);

        $rows = $this->readXlsxRows($request->file('file')->getRealPath());
        $header = array_map(fn ($value) => strtolower(trim((string) $value)), array_shift($rows) ?? []);
        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $data = $this->mapRow($header, $row);

            if (empty($data['nim']) || empty($data['kode_mk']) || $data['nilai_angka'] === '') {
                $skipped++;
                continue;
            }

            $mahasiswa = Mahasiswa::query()->where('nim', $data['nim'])->first();
            $mataKuliah = MataKuliah::query()->where('kode', $data['kode_mk'])->first();

            if (! $mahasiswa || ! $mataKuliah) {
                $skipped++;
                continue;
            }

            $semesterUrutan = (int) ($data['semester_urutan'] ?: $mataKuliah->semester_default);
            $tahunAkademik = $data['tahun_akademik'] ?: '2023/2024';
            $periode = strtolower($data['periode'] ?: ($semesterUrutan % 2 === 1 ? 'ganjil' : 'genap'));

            if (! in_array($periode, ['ganjil', 'genap'], true)) {
                $skipped++;
                continue;
            }

            $semester = Semester::updateOrCreate(
                [
                    'tahun_akademik' => $tahunAkademik,
                    'periode' => $periode,
                    'urutan' => $semesterUrutan,
                ],
                ['nama' => 'Semester ' . $semesterUrutan]
            );

            $krs = Krs::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswa->id,
                    'mata_kuliah_id' => $mataKuliah->id,
                    'semester_id' => $semester->id,
                ],
                ['status' => 'diambil']
            );

            $nilaiAngka = round((float) $data['nilai_angka'], 2);
            $hasilKonversi = Nilai::konversiDariAngka($nilaiAngka);

            Nilai::updateOrCreate(
                ['krs_id' => $krs->id],
                [
                    'nilai_angka' => $nilaiAngka,
                    'nilai_huruf' => $hasilKonversi['nilai_huruf'],
                    'bobot' => $hasilKonversi['bobot'],
                    'status_lulus' => $hasilKonversi['status_lulus'],
                ]
            );

            $imported++;
        }

        return redirect()
            ->route('pengelola.input-nilai')
            ->with('success', "Import nilai selesai. Berhasil: {$imported}, dilewati: {$skipped}.");
    }

    private function ensurePengelola(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->isPengelola()) {
            abort(403);
        }
    }

    private function mapRow(array $header, array $row): array
    {
        $mapped = [];

        foreach ($header as $index => $key) {
            if ($key !== '') {
                $mapped[$key] = trim((string) ($row[$index] ?? ''));
            }
        }

        return $mapped;
    }

    private function readXlsxRows(string $path): array
    {
        if (class_exists('ZipArchive')) {
            $zip = new \ZipArchive();

            if ($zip->open($path) !== true) {
                throw new \RuntimeException("Unable to open XLSX file: {$path}");
            }

            $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
            $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
            $zip->close();
        } else {
            $sharedStringsXml = $this->getZipEntryContents($path, 'xl/sharedStrings.xml');
            $sheetXmlContent = $this->getZipEntryContents($path, 'xl/worksheets/sheet1.xml');

            if ($sheetXmlContent === null) {
                throw new \RuntimeException("Unable to read sheet data from XLSX file: {$path}");
            }
        }

        $sharedStrings = [];

        if ($sharedStringsXml !== false && $sharedStringsXml !== null) {
            $xml = simplexml_load_string($sharedStringsXml);

            foreach ($xml->si as $stringItem) {
                $text = '';

                if (isset($stringItem->t)) {
                    $text = (string) $stringItem->t;
                } elseif (isset($stringItem->r)) {
                    foreach ($stringItem->r as $run) {
                        $text .= (string) $run->t;
                    }
                }

                $sharedStrings[] = $text;
            }
        }

        $sheetXml = simplexml_load_string($sheetXmlContent);
        $rows = [];

        foreach ($sheetXml->sheetData->row as $row) {
            $cells = [];

            foreach ($row->c as $cell) {
                $reference = (string) $cell['r'];
                $columnIndex = $this->columnIndexFromReference($reference);
                $type = (string) $cell['t'];
                $value = '';

                if ($type === 's') {
                    $value = $sharedStrings[(int) $cell->v] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = (string) $cell->is->t;
                } else {
                    $value = (string) $cell->v;
                }

                $cells[$columnIndex] = $value;
            }

            if ($cells !== []) {
                ksort($cells);
                $maxIndex = max(array_keys($cells));
                $normalized = [];

                for ($index = 0; $index <= $maxIndex; $index++) {
                    $normalized[] = $cells[$index] ?? '';
                }

                $rows[] = $normalized;
            }
        }

        return $rows;
    }

    private function writeZipArchive(array $files, string $path): void
    {
        if (class_exists('ZipArchive')) {
            $zip = new \ZipArchive();

            if ($zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException("Unable to create XLSX file: {$path}");
            }

            foreach ($files as $name => $content) {
                $zip->addFromString($name, $content);
            }

            $zip->close();

            return;
        }

        $this->writeZipFallback($files, $path);
    }

    private function writeZipFallback(array $files, string $path): void
    {
        $handle = fopen($path, 'wb');

        if ($handle === false) {
            throw new \RuntimeException("Unable to open file for writing: {$path}");
        }

        $centralDir = '';
        $offset = 0;

        foreach ($files as $name => $content) {
            $crc = crc32($content);
            $compressed = $content;
            $compressedSize = strlen($compressed);
            $uncompressedSize = $compressedSize;
            $nameLength = strlen($name);
            $extra = '';
            $extraLength = 0;

            $header = pack('VvvvvvVVVvv',
                0x04034b50,
                20,
                0,
                0,
                $this->zipDosTime(),
                $this->zipDosDate(),
                $crc,
                $compressedSize,
                $uncompressedSize,
                $nameLength,
                $extraLength
            );

            fwrite($handle, $header);
            fwrite($handle, $name);
            fwrite($handle, $extra);
            fwrite($handle, $compressed);

            $centralDir .= pack('VvvvvvvVVVvvvvvVV',
                0x02014b50,
                0,
                20,
                0,
                0,
                $this->zipDosTime(),
                $this->zipDosDate(),
                $crc,
                $compressedSize,
                $uncompressedSize,
                $nameLength,
                $extraLength,
                0,
                0,
                0,
                0,
                $offset
            );
            $centralDir .= $name;

            $offset += strlen($header) + $nameLength + $extraLength + $compressedSize;
        }

        $centralDirOffset = $offset;
        fwrite($handle, $centralDir);
        $centralDirSize = strlen($centralDir);

        $endOfCentral = pack('VvvvvVVv',
            0x06054b50,
            0,
            0,
            count($files),
            count($files),
            $centralDirSize,
            $centralDirOffset,
            0
        );

        fwrite($handle, $endOfCentral);
        fclose($handle);
    }

    private function getZipEntryContents(string $path, string $entryName): ?string
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new \RuntimeException("Unable to read XLSX file: {$path}");
        }

        $eocdPos = strrpos($contents, pack('V', 0x06054b50));

        if ($eocdPos === false) {
            return null;
        }

        $eocd = substr($contents, $eocdPos, 22);
        $header = unpack('Vsig/vdisk/vdiskStart/ventries/ventriesTotal/Vsize/Voffset/vcommentLen', $eocd);

        if ($header['sig'] !== 0x06054b50) {
            return null;
        }

        $centralOffset = $header['offset'];
        $entryCount = $header['entriesTotal'];
        $pos = $centralOffset;

        for ($i = 0; $i < $entryCount; $i++) {
            $centralHeader = substr($contents, $pos, 46);
            $info = unpack('Vsig/vversionMade/vversion/vflags/vmethod/vmtime/vmdate/Vcrc/Vcsize/Visize/vnameLen/vextraLen/vcommentLen/vdiskStart/vintAttr/VextAttr/Voffset', $centralHeader);

            if ($info['sig'] !== 0x02014b50) {
                return null;
            }

            $name = substr($contents, $pos + 46, $info['nameLen']);
            $pos += 46 + $info['nameLen'] + $info['extraLen'] + $info['commentLen'];

            if ($name !== $entryName) {
                continue;
            }

            $localHeader = substr($contents, $info['offset'], 30);
            $localInfo = unpack('Vsig/vversion/vflags/vmethod/vmtime/vmdate/Vcrc/Vcsize/Visize/vnameLen/vextraLen', $localHeader);

            if ($localInfo['sig'] !== 0x04034b50) {
                return null;
            }

            $dataPos = $info['offset'] + 30 + $localInfo['nameLen'] + $localInfo['extraLen'];
            $data = substr($contents, $dataPos, $info['csize']);

            if ($info['method'] === 0) {
                return $data;
            }

            if ($info['method'] === 8) {
                return gzinflate($data);
            }

            return null;
        }

        return null;
    }

    private function zipDosTime(int $timestamp = null): int
    {
        $timestamp ??= time();

        return ((int) date('G', $timestamp) << 11)
            | ((int) date('i', $timestamp) << 5)
            | ((int) date('s', $timestamp) >> 1);
    }

    private function zipDosDate(int $timestamp = null): int
    {
        $timestamp ??= time();

        return (((int) date('Y', $timestamp) - 1980) << 9)
            | ((int) date('n', $timestamp) << 5)
            | (int) date('j', $timestamp);
    }

    private function columnIndexFromReference(string $reference): int
    {
        preg_match('/[A-Z]+/', $reference, $matches);
        $letters = $matches[0] ?? 'A';
        $index = 0;

        foreach (str_split($letters) as $letter) {
            $index = $index * 26 + (ord($letter) - 64);
        }

        return $index - 1;
    }

    private function buildXlsx(string $filename, array $rows): string
    {
        $relativePath = 'imports/' . uniqid('', true) . '-' . $filename;
        $path = Storage::path($relativePath);

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $this->writeZipArchive([
            '[Content_Types].xml' => $this->contentTypesXml(),
            '_rels/.rels' => $this->relsXml(),
            'xl/workbook.xml' => $this->workbookXml(),
            'xl/_rels/workbook.xml.rels' => $this->workbookRelsXml(),
            'xl/worksheets/sheet1.xml' => $this->sheetXml($rows),
        ], $path);

        return $path;
    }

    private function sheetXml(array $rows): string
    {
        $xmlRows = '';

        foreach ($rows as $rowIndex => $row) {
            $number = $rowIndex + 1;
            $xmlRows .= '<row r="' . $number . '">';

            foreach ($row as $columnIndex => $value) {
                $cell = $this->columnName($columnIndex + 1) . $number;
                $xmlRows .= '<c r="' . $cell . '" t="inlineStr"><is><t>' . htmlspecialchars((string) $value, ENT_XML1) . '</t></is></c>';
            }

            $xmlRows .= '</row>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetData>' . $xmlRows . '</sheetData></worksheet>';
    }

    private function columnName(int $index): string
    {
        $name = '';

        while ($index > 0) {
            $index--;
            $name = chr(65 + ($index % 26)) . $name;
            $index = intdiv($index, 26);
        }

        return $name;
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '</Types>';
    }

    private function relsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Template" sheetId="1" r:id="rId1"/></sheets></workbook>';
    }

    private function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '</Relationships>';
    }
}
