<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class BookChapter extends Model
{
    use GlobalStatus;

    protected $guarded = ['id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function lessons()
    {
        return $this->hasMany(BookLesson::class);
    }
}
