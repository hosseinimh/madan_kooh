<?php

namespace App\Http\Resources\User;

use App\Constants\Permission;
use App\Constants\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $roles = $this->getRoleNames();
        $permissions = $this->getPermissionNames();
        return [
            'id' => intval($this->id),
            'username' => $this->username,
            'name' => $this->name ?? '',
            'family' => $this->family ?? '',
            'mobile' => $this->mobile ?? '',
            'isActive' => intval($this->is_active),
            'roles' => $roles,
            'rolesText' => $this->getRolesText($roles),
            'permissions' => $permissions,
            'permissionsText' => $this->getPermissionsText($permissions),
        ];
    }

    private function getRolesText(Collection $roles)
    {
        $roles = $roles->toArray();
        $rolesText = array_map(function ($role) {
            if (in_array($role, Role::toArray())) {
                return __('role.role_' . $role);
            }
            return __('role.role_undefined');
        }, $roles);
        return implode(' | ', $rolesText);
    }

    private function getPermissionsText(Collection $permissions)
    {
        $permissions = $permissions->toArray();
        $permissionsText = array_map(function ($permission) {
            if (in_array($permission, Permission::toArray())) {
                return __('permission.permission_' . $permission);
            }
            return __('permission.permission_undefined');
        }, $permissions);
        return implode(' | ', $permissionsText);
    }
}
