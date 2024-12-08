<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PersonaController extends Controller
{
    // Método para obtener las personas con detalles (teléfonos y direcciones)
    public function obtenerPersonas($page)
    {
        // Validar el parámetro 'pagina' en la solicitud
        $pagina = intval($page); // Si no se pasa 'pagina', se asume que es la página 1.
        $registrosPorPagina = 100;
        // Llamar al procedimiento almacenado para obtener las personas con detalles.
        $personas = DB::select('CALL getPersonasWithDetails(?, ?)', [
            $pagina,
            $registrosPorPagina
        ]);

        // Obtener el total de registros para la paginación
        $totalRegistros = DB::selectOne('
            SELECT COUNT(*) as total
            FROM personas p
            LEFT JOIN telefono t ON t.persona_id = p.id
            LEFT JOIN direccion d ON d.persona_id = p.id
        ')->total;
        // Verificar si hay registros
        if ($totalRegistros > 0) {
            $totalPaginas = ceil($totalRegistros / intval($registrosPorPagina));
        } else {
            // Si no hay registros, asignamos 0 o 1 página dependiendo de tu preferencia
            $totalPaginas = 1;
        }
        return response()->json([
            'data' => $personas,
            'current_page' => $pagina,
            'last_page' => $totalPaginas,
            'total' => $totalRegistros,
        ])->header('Content-Type', 'application/json; charset=UTF-8');
    }
}
