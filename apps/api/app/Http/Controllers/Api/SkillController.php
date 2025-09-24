<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $skills = Skill::query()
            ->select(['id', 'name', 'level'])
            ->orderBy('name')
            ->get();

        return $this->successResponse($skills, 'Skills obtenidas correctamente');
    }
}
