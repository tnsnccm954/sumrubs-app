<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupBussinessStatus extends Model
{
    use HasFactory;

    protected $table = 'lookup_bussiness_statuses';

    protected $fillable = ['name', 'display_name'];
}
