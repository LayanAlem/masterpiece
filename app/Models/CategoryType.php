<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CategoryType extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'main_category_id',
        'description',
        'image',
    ];

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
