<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Entity;
use App\Services\PublicApiService; // Importar el servicio
use Illuminate\Http\JsonResponse;


class EntityController extends Controller
{
    private $service;

    // Inyectar el servicio en el constructor del controlador
    public function __construct(PublicApiService $service)
    {
        $this->service = $service;
    }

        // Método para invocar el servicio y procesar entidades
        public function fetchData(): JsonResponse
        {
            try {
                $this->service->fetchEntities(); // Llama al método del servicio
                return response()->json([
                    'success' => true,
                    'message' => 'Datos obtenidos y guardados correctamente en la base de datos.',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error: ' . $e->getMessage(),
                ], 500);
            }
        }
    
        public function fetchDataByCategory($category)
        {
            $this->service->fetchEntitiesByCategory($category);
        }
        
    public function getByCategory($category)
    {
        // Buscar las entidades relacionadas con la categoría
        $entities = Entity::with('category')
            ->whereHas('category', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->get();

        // Si no se encuentran entidades, devolver un mensaje de error
        if ($entities->isEmpty()) {
            Log::warning('No entities found for category: ' . $category);
            return response()->json([
                'success' => false,
                'message' => 'No entities found for the given category.',
            ], 404); // Respuesta 404
        }

        // Registrar en los logs los datos obtenidos
        Log::info('Entities found: ' . $entities->count());

        // Devolver los datos encontrados
        return response()->json([
            'success' => true,
            'data' => $entities,
        ]);
    }
}
