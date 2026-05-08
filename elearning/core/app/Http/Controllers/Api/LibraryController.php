<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookChapter;
use App\Models\BookLesson;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function categories()
    {
        $categories = BookCategory::active()->orderBy('name')->get();
        $notify[] = 'Categories retrieved successfully';
        return responseSuccess('categories', $notify, [
            'categories' => $categories
        ]);
    }

    public function categoryBooks($id)
    {
        $category = BookCategory::active()->find($id);
        if (!$category) {
            $notify[] = 'Category not found';
            return responseError('category_not_found', $notify);
        }

        $books = Book::active()->where('book_category_id', $id)->orderBy('id', 'desc')->paginate(getPaginate());
        
        $notify[] = 'Books retrieved successfully';
        return responseSuccess('category_books', $notify, [
            'category' => $category,
            'books' => $books
        ]);
    }

    public function books()
    {
        $books = Book::active()->with('category')->orderBy('id', 'desc')->paginate(getPaginate());
        
        $notify[] = 'Books retrieved successfully';
        return responseSuccess('books', $notify, [
            'books' => $books
        ]);
    }

    public function bookDetails($id)
    {
        $book = Book::active()->with(['category', 'chapters' => function($q) {
            $q->active()->orderBy('sort_order')->with(['lessons' => function($q) {
                $q->active()->orderBy('sort_order');
            }]);
        }])->find($id);

        if (!$book) {
            $notify[] = 'Book not found';
            return responseError('book_not_found', $notify);
        }

        $notify[] = 'Book details retrieved successfully';
        return responseSuccess('book_details', $notify, [
            'book' => $book
        ]);
    }

    public function chapterDetails($id)
    {
        $chapter = BookChapter::active()->with(['book', 'lessons' => function($q) {
            $q->active()->orderBy('sort_order');
        }])->find($id);

        if (!$chapter) {
            $notify[] = 'Chapter not found';
            return responseError('chapter_not_found', $notify);
        }

        $notify[] = 'Chapter details retrieved successfully';
        return responseSuccess('chapter_details', $notify, [
            'chapter' => $chapter
        ]);
    }

    public function lessonDetails($id)
    {
        $lesson = BookLesson::active()->with('chapter.book')->find($id);

        if (!$lesson) {
            $notify[] = 'Lesson not found';
            return responseError('lesson_not_found', $notify);
        }

        if ($lesson->pdf_file) {
            $lesson->pdf_url = asset(getFilePath('lesson_pdf') . '/' . $lesson->pdf_file);
        }

        $notify[] = 'Lesson details retrieved successfully';
        return responseSuccess('lesson_details', $notify, [
            'lesson' => $lesson
        ]);
    }
}
