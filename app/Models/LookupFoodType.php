<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupFoodType extends Model
{
    use HasFactory;

    protected $table = 'lookup_food_types';
    protected $fillable = ['parent_food_type_id', 'category_type', 'name', 'display_name', 'is_modifilable'];
}
