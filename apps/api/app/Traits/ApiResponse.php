<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\AbstractPaginator;

trait ApiResponse
{
    /**
     * Respuesta de éxito estándar
     */
    protected function successResponse($data = null, string $message = 'Operación exitosa', int $code = 200): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Respuesta de error estándar
     */
    protected function errorResponse(string $message = 'Error en la operación', int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

    /**
     * Respuesta con paginación
     */
    protected function paginatedResponse(AbstractPaginator $paginator, string $message = 'Datos obtenidos correctamente', int $code = 200): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ], $code);
    }

    /**
     * Respuesta personalizada
     */
    protected function customResponse(array $payload, int $code = 200): JsonResponse
    {
        return response()->json($payload, $code);
    }
}
