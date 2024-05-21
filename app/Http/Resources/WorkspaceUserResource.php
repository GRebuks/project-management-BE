<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceUserResource extends JsonResource
{
    protected $workspace_id;

    public function __construct($resource, $workspace_id = null)
    {
        parent::__construct($resource);
        $this->workspace_id = $workspace_id;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role_id = $this->workspaces()->where('workspace_id', $this->workspace_id)->value('role_id');
        $role = Role::find($role_id);
        return [
            'id' => $this['id'],
            'username' => $this['username'],
            'email' => $this['email'],
            'role_id' => $role_id,
            'role' => new RoleResource($role),
        ];
    }
}
