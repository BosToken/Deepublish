<?php 

namespace App\Services;

use App\Models\Role;

class RoleService
{
    public function getRoleByName($name): ?Role
    {
        return Role::where('name', $name)->first();
    }
    public function getAllRoles()
    {
        return Role::all();
    }
    
}
