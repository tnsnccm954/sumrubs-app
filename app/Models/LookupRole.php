<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupRole extends Model
{
    use HasFactory;
    protected $table = "lookup_roles";
    protected $fillable = ['name', 'system_name', 'modifilable'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role', 'user_id', 'id');
    }
    public function permissions()
    {
        return $this->belongsToMany(LookupPermission::class, 'role_permission', 'role_id', 'permission_id');
    }
}
