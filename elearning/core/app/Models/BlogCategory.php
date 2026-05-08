<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use GlobalStatus;
    protected $guarded = ['id'];

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
