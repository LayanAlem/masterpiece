<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'category_types_count'
    ];


    public function categoryTypes()
    {
        return $this->hasMany(CategoryType::class);
    }

    public function activities()
    {
        return $this->hasManyThrough(Activity::class, CategoryType::class);
    }


}
