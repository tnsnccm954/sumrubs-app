<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupCuisineCulture extends Model
{
    use HasFactory;

    protected $table = 'lookup_cuisine_cultures';

    protected $fillable = ['parent_cuisine_culture_id', 'name', 'display_name'];
}
