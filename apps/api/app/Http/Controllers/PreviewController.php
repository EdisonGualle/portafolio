<?php

namespace App\Http\Controllers;

use App\Models\PreviewToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PreviewController extends Controller
{
    public function show(string $token, Request $request)
    {
        $tokenModel = PreviewToken::query()
            ->where('token', $token)
            ->valid()
            ->first();

        if (! $tokenModel) {
            abort(Response::HTTP_NOT_FOUND, 'Token inválido o expirado.');
        }

        $model = $tokenModel->previewable;
        if (! $model) {
            abort(Response::HTTP_NOT_FOUND, 'Contenido no disponible.');
        }

        // Vista mínima para validar el flujo (luego harás el render bonito)
        return response()->view('preview.show', [
            'model' => $model,
            'token' => $tokenModel,
        ]);
    }
}
