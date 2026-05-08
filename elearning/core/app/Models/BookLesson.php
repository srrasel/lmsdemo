<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class BookLesson extends Model
{
    use GlobalStatus;

    protected $guarded = ['id'];
    protected $appends = ['pdf_url'];

    public function getPdfUrlAttribute()
    {
        return $this->pdf_file ? asset(getFilePath('lesson_pdf') . '/' . $this->pdf_file) : null;
    }

    public function chapter()
    {
        return $this->belongsTo(BookChapter::class, 'book_chapter_id');
    }
}
