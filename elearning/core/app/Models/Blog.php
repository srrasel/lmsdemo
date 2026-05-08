<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use GlobalStatus;
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function getImagePathAttribute(){
        return asset(getFilePath('blog'));
    }
}
