<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ManageBlogController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Blogs';
        $blogs = Blog::with('category')->searchable(['title', 'category:name'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.blog.index', compact('pageTitle', 'blogs'));
    }

    public function create()
    {
        $pageTitle = 'Create Blog';
        $categories = BlogCategory::active()->get();
        return view('admin.blog.create', compact('pageTitle', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'required|integer|exists:blog_categories,id',
            'description' => 'required|string',
            'image' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = slug($request->title);
        $blog->blog_category_id = $request->blog_category_id;
        $blog->description = $request->description;

        if ($request->hasFile('image')) {
            try {
                $blog->image = fileUploader($request->image, getFilePath('blog'), getFileSize('blog'));
            } catch (\Throwable $th) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $blog->save();

        $notify[] = ['success', 'Blog added successfully'];
        return to_route('admin.blog.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Blog';
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::active()->get();
        return view('admin.blog.edit', compact('pageTitle', 'blog', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'required|integer|exists:blog_categories,id',
            'description' => 'required|string',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $blog = Blog::findOrFail($id);
        $blog->title = $request->title;
        $blog->slug = slug($request->title);
        $blog->blog_category_id = $request->blog_category_id;
        $blog->description = $request->description;

        if ($request->hasFile('image')) {
            try {
                $blog->image = fileUploader($request->image, getFilePath('blog'), getFileSize('blog'), $blog->image);
            } catch (\Throwable $th) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $blog->save();

        $notify[] = ['success', 'Blog updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Blog::changeStatus($id);
    }

    public function categories()
    {
        $pageTitle = 'Blog Categories';
        $categories = BlogCategory::searchable(['name'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.blog.category', compact('pageTitle', 'categories'));
    }

    public function categoryStore(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:40|unique:blog_categories,name,' . $id,
        ]);

        $category = $id ? BlogCategory::findOrFail($id) : new BlogCategory();
        $category->name = $request->name;
        $category->slug = slug($request->name);
        $category->save();

        $notify[] = $id ? ['success', 'Category updated successfully'] : ['success', 'Category added successfully'];
        return back()->withNotify($notify);
    }

    public function categoryStatus($id)
    {
        return BlogCategory::changeStatus($id);
    }
}
