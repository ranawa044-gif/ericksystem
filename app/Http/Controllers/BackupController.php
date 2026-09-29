<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class BackupController extends Controller
{
    public function download()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $dbName = env('DB_DATABASE', 'simnilai');
        $date = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$dbName}_{$date}.sql";
        
        try {
            $tables = DB::select('SHOW TABLES');
            $sql = "-- E-RICK System Database Backup\n";
            $sql .= "-- Generated at: " . now()->format('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $tableInfo) {
                $table = array_values((array)$tableInfo)[0];
                
                $createTable = DB::select("SHOW CREATE TABLE `$table`");
                $sql .= "-- Table structure for `$table`\n";
                $sql .= "DROP TABLE IF EXISTS `$table`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                $rows = DB::select("SELECT * FROM `$table`");
                if (count($rows) > 0) {
                    $sql .= "-- Dumping data for table `$table`\n";
                    $sql .= "INSERT INTO `$table` VALUES \n";
                    $values = [];
                    foreach ($rows as $row) {
                        $rowValues = [];
                        foreach ((array)$row as $val) {
                            if (is_null($val)) {
                                $rowValues[] = 'NULL';
                            } else {
                                $rowValues[] = "'" . addslashes($val) . "'";
                            }
                        }
                        $values[] = "(" . implode(', ', $rowValues) . ")";
                    }
                    $sql .= implode(",\n", $values) . ";\n\n";
                }
            }
            
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            // Simpan ke storage/app dan unduh
            Storage::put($filename, $sql);
            $storagePath = storage_path("app/{$filename}");
            
            return response()->download($storagePath)->deleteFileAfterSend(true);
            
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membackup database: ' . $e->getMessage());
        }
    }
}
