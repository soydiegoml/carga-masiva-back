<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validar que el archivo sea un Excel o CSV
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // 10 MB
        ]);

        // Obtener el archivo
        $file = $request->file('file');
        $filePath = $file->storeAs('uploads', $file->getClientOriginalName());

        // Convertir el archivo Excel a CSV (sin iterar sobre los datos)
        try {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load(Storage::path($filePath));
            
            // Guardar el archivo en formato CSV temporal
            $csvPath = storage_path('app/uploads/converted.csv');
            $csvPathForMySQL = str_replace('\\','/', $csvPath);
            $writer = IOFactory::createWriter($spreadsheet, 'Csv');
            $writer->save($csvPath);

            // Crear la tabla temporal
            DB::statement("CREATE TEMPORARY TABLE IF NOT EXISTS personas_temp (
                nombre VARCHAR(255),
                paterno VARCHAR(255),
                materno VARCHAR(255),
                telefono VARCHAR(20),
                calle VARCHAR(255),
                numero_exterior VARCHAR(20),
                numero_interior VARCHAR(20),
                colonia VARCHAR(255),
                cp VARCHAR(10)
            )");
            if (!file_exists($csvPathForMySQL)) {
                die("El archivo no existe en la ruta especificada.");
            }
            // Ahora, usar el archivo CSV con LOAD DATA LOCAL INFILE
            $table = 'personas_temp'; // Nombre de la tabla temporal
            $sql = "LOAD DATA LOCAL INFILE '{$csvPathForMySQL}' INTO TABLE {$table} FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES";
            DB::statement($sql);

            // Eliminar el archivo CSV temporal después de cargarlo
            unlink($csvPath);
            DB::statement("CALL migrate_data()");
            return response()->json(['message' => 'Archivo cargado exitosamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar el archivo: ' . $e->getMessage()], 500);
        }
    }
}