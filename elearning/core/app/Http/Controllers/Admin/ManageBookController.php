<?php

namespace App\Http\Controllers\Admin;

use App\Constants\FileInfo;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookChapter;
use App\Models\BookLesson;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ManageBookController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Books';
        $books = Book::with('category')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.book.index', compact('pageTitle', 'books'));
    }

    public function create()
    {
        $pageTitle = 'Create Book';
        $categories = BookCategory::active()->orderBy('name')->get();
        return view('admin.book.create', compact('pageTitle', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_category_id' => 'required|exists:book_categories,id',
            'title' => 'required|string|max:255',
            'image' => ['required', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'file' => ['nullable', new FileTypeValidate(['pdf'])],
            'description' => 'required',
        ]);

        $book = new Book();
        $book->book_category_id = $request->book_category_id;
        $book->title = $request->title;
        $book->slug = slug($request->title);
        $book->description = $request->description;

        if ($request->hasFile('image')) {
            try {
                $book->image = fileUploader($request->image, getFilePath('book'), getFileSize('book'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('file')) {
            try {
                $book->file = fileUploader($request->file, getFilePath('book_pdf'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'PDF could not be uploaded'];
                return back()->withNotify($notify);
            }
        }

        $book->save();

        $notify[] = ['success', 'Book created successfully'];
        return to_route('admin.book.edit', $book->id)->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Book';
        $book = Book::with('chapters.lessons')->findOrFail($id);
        $categories = BookCategory::active()->orderBy('name')->get();
        return view('admin.book.edit', compact('pageTitle', 'book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'book_category_id' => 'required|exists:book_categories,id',
            'title' => 'required|string|max:255',
            'image' => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'file' => ['nullable', new FileTypeValidate(['pdf'])],
            'description' => 'required',
        ]);

        $book = Book::findOrFail($id);
        $book->book_category_id = $request->book_category_id;
        $book->title = $request->title;
        $book->slug = slug($request->title);
        $book->description = $request->description;

        if ($request->hasFile('image')) {
            try {
                $old = $book->image;
                $book->image = fileUploader($request->image, getFilePath('book'), getFileSize('book'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('file')) {
            try {
                $old = $book->file;
                $book->file = fileUploader($request->file, getFilePath('book_pdf'), null, $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'PDF could not be uploaded'];
                return back()->withNotify($notify);
            }
        }

        $book->save();

        $notify[] = ['success', 'Book updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Book::changeStatus($id);
    }

    public function categories()
    {
        $pageTitle = 'Manage Book Categories';
        $categories = BookCategory::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.book.category', compact('pageTitle', 'categories'));
    }

    public function categoryStore(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:40|unique:book_categories,name,' . $id,
        ]);

        $category = $id ? BookCategory::findOrFail($id) : new BookCategory();
        $category->name = $request->name;
        $category->slug = slug($request->name);
        $category->save();

        $notify[] = $id ? ['success', 'Category updated successfully'] : ['success', 'Category added successfully'];
        return back()->withNotify($notify);
    }

    public function categoryStatus($id)
    {
        return BookCategory::changeStatus($id);
    }

    public function chapterStore(Request $request, $bookId, $id = null)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        if ($id) {
            $chapter = BookChapter::where('book_id', $bookId)->findOrFail($id);
            $notify[] = ['success', 'Chapter updated successfully'];
        } else {
            $chapter = new BookChapter();
            $chapter->book_id = $bookId;
            $notify[] = ['success', 'Chapter added successfully'];
        }

        $chapter->title = $request->title;
        $chapter->slug = slug($request->title);
        $chapter->sort_order = $request->sort_order;
        $chapter->save();

        return back()->withNotify($notify);
    }

    public function chapterDelete($id)
    {
        $chapter = BookChapter::with('lessons')->findOrFail($id);
        
        foreach ($chapter->lessons as $lesson) {
            fileManager()->removeFile(getFilePath('lesson_pdf') . '/' . $lesson->pdf_file);
            $lesson->delete();
        }
        
        $chapter->delete();
        $notify[] = ['success', 'Chapter deleted successfully'];
        return back()->withNotify($notify);
    }

    public function lessonStore(Request $request, $chapterId, $id = null)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'pdf_file' => ['nullable', new FileTypeValidate(['pdf'])],
        ]);

        if ($id) {
            $lesson = BookLesson::where('book_chapter_id', $chapterId)->findOrFail($id);
            $notify[] = ['success', 'Lesson updated successfully'];
        } else {
            $lesson = new BookLesson();
            $lesson->book_chapter_id = $chapterId;
            $notify[] = ['success', 'Lesson added successfully'];
        }

        $lesson->title = $request->title;
        $lesson->slug = slug($request->title);
        $lesson->content = $request->content;
        $lesson->sort_order = $request->sort_order;

        if ($request->hasFile('pdf_file')) {
            try {
                $old = $lesson->pdf_file;
                $lesson->pdf_file = fileUploader($request->pdf_file, getFilePath('lesson_pdf'), null, $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'PDF could not be uploaded'];
                return back()->withNotify($notify);
            }
        }

        $lesson->save();

        return back()->withNotify($notify);
    }

    public function lessonDelete($id)
    {
        $lesson = BookLesson::findOrFail($id);
        fileManager()->removeFile(getFilePath('lesson_pdf') . '/' . $lesson->pdf_file);
        $lesson->delete();
        $notify[] = ['success', 'Lesson deleted successfully'];
        return back()->withNotify($notify);
    }
}
