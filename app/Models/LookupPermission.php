<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupPermission extends Model
{
    use HasFactory;
    protected $table = 'lookup_permissions';
    protected $fillable = ['action', 'object', 'display_name', 'discription'];

    public function userRole()
    {
        return $this->belongsToMany(LookupRole::class, 'role_permission', 'permission_id', 'user_role_id');
    }
}
