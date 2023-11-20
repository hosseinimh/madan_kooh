<?php

namespace App\Http\Resources\Permission;

use App\Constants\Permission;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => intval($this->id),
            'name' => $this->name,
            'gaurdName' => $this->gaurd_name,
            'text' => $this->getPermissionText($this->name),
        ];
    }

    private function getPermissionText(string $permission)
    {
        $text = __('permission.permission_undefined');
        if (in_array($permission, Permission::toArray())) {
            $text = __('permission.permission_' . $permission);
        }
        return $text;
    }
}
