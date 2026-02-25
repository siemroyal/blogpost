<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'image',
        'status',
    ];
    //protected $guarded = ['id','created_at','updated_at'];

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id','id','categories');
    }
    public function children(){
        return $this->hasMany(Category::class,'parent_id','id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/no-image.jpg');
    }
    public function posts():HasMany{
        return $this->hasMany(Post::class);
    }
}
