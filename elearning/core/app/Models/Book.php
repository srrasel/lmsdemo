<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use GlobalStatus;

    protected $guarded = ['id'];
    protected $appends = ['file_url', 'image_url'];

    public function getFileUrlAttribute()
    {
        return $this->file ? asset(getFilePath('book_pdf') . '/' . $this->file) : null;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset(getFilePath('book') . '/' . $this->image) : null;
    }

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    public function chapters()
    {
        return $this->hasMany(BookChapter::class);
    }
}
