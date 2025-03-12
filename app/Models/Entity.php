<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $fillable = ['api', 'description', 'link', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
