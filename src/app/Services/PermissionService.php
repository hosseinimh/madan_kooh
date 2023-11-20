<?php

namespace App\Services;

use App\Models\Permission as Model;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    public function get(int $id): mixed
    {
        return Model::where('id', $id)->first();
    }

    public function getAll(): mixed
    {
        return Model::select('permissions.*', DB::raw('COUNT(*) OVER() AS items_count'))->orderBy('id', 'ASC')->get();
    }
}
