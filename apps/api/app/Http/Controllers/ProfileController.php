<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Traits\ApiResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function show()
    {
        $profile = Profile::first();

        if (!$profile) {
            return $this->errorResponse(
                'Perfil no encontrado',
                404
            );
        }

        return $this->successResponse(
            new ProfileResource($profile),
            'Perfil obtenido correctamente',
            200
        );
    }
}
