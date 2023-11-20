<?php

namespace App\Services;

use App\Models\User as Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function get(int $id): mixed
    {
        return Model::where('id', $id)->first();
    }

    public function getPaginate(string|null $username, string|null $name, string|null $family, int $page, int $pageItems): mixed
    {
        return Model::where('username', 'LIKE', '%' . $username . '%')->where('name', 'LIKE', '%' . $name . '%')->where('family', 'LIKE', '%' . $family . '%')->select('tbl_users.*', DB::raw('COUNT(*) OVER() AS items_count'))->orderBy('family', 'ASC')->orderBy('name', 'ASC')->orderBy('id', 'ASC')->skip(($page - 1) * $pageItems)->take($pageItems)->get();
    }

    public function store(string $username, string $password, string $name, string $family, string $mobile, int $isActive, ?array $roles, ?array $permissions): mixed
    {
        $isActive = $isActive > 0 ? 1 : 0;
        $data = [
            'username' => $username,
            'password' => $password,
            'name' => $name,
            'family' => $family,
            'mobile' => $mobile,
            'is_active' => $isActive,
        ];
        $model = Model::create($data);
        if ($model) {
            $model->syncRoles($roles);
            $model->syncPermissions($permissions);
        }
        return $model ?? null;
    }

    public function update(Model $model, string $name, string $family, string $mobile): bool
    {
        $data = [
            'name' => $name,
            'family' => $family,
            'mobile' => $mobile,
        ];
        return $model->update($data);
    }

    public function updateSync(Model $model, string $name, string $family, string $mobile, int $isActive, ?array $roles, ?array $permissions): bool
    {
        $isActive = $isActive > 0 ? 1 : 0;
        $data = [
            'name' => $name,
            'family' => $family,
            'mobile' => $mobile,
            'is_active' => $isActive,
        ];
        $result = $model->update($data);
        if ($result) {
            $model->syncRoles($roles);
            $model->syncPermissions($permissions);
        }
        return $result;
    }

    public function changePassword(Model $user, string $password): bool
    {
        $password = Hash::make($password);

        return DB::statement("UPDATE `tbl_users` SET `password`='$password' WHERE `id`=$user->id");
    }
}
