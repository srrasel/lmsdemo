<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blogs()
    {
        $blogs = Blog::active()->with('category')->orderBy('id', 'desc')->paginate(getPaginate());
        $categories = BlogCategory::active()->get();
        
        $notify[] = 'Blogs retrieved successfully';
        return responseSuccess('blogs', $notify, [
            'blogs' => $blogs,
            'categories' => $categories
        ]);
    }

    public function blogDetails($id)
    {
        $blog = Blog::active()->with('category')->find($id);
        if (!$blog) {
            $notify[] = 'Blog not found';
            return responseError('blog_not_found', $notify);
        }

        $relatedBlogs = Blog::active()
            ->where('blog_category_id', $blog->blog_category_id)
            ->where('id', '!=', $blog->id)
            ->limit(5)
            ->get();

        $notify[] = 'Blog details retrieved successfully';
        return responseSuccess('blog_details', $notify, [
            'blog' => $blog,
            'related_blogs' => $relatedBlogs
        ]);
    }

    public function categoryBlogs($id)
    {
        $category = BlogCategory::active()->find($id);
        if (!$category) {
            $notify[] = 'Category not found';
            return responseError('category_not_found', $notify);
        }

        $blogs = Blog::active()
            ->where('blog_category_id', $category->id)
            ->with('category')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        $notify[] = 'Category blogs retrieved successfully';
        return responseSuccess('category_blogs', $notify, [
            'category' => $category,
            'blogs' => $blogs
        ]);
    }
}
