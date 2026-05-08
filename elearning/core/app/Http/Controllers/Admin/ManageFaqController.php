<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ManageFaqController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage FAQs';
        $faqs = Faq::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.faq.index', compact('pageTitle', 'faqs'));
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'image' => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($id) {
            $faq = Faq::findOrFail($id);
            $notify[] = ['success', 'FAQ updated successfully'];
        } else {
            $faq = new Faq();
            $notify[] = ['success', 'FAQ added successfully'];
        }

        $faq->question = $request->question;
        $faq->answer = $request->answer;

        if ($request->hasFile('image')) {
            try {
                $old = $id ? $faq->image : null;
                $faq->image = fileUploader($request->image, getFilePath('faq'), getFileSize('faq'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded'];
                return back()->withNotify($notify);
            }
        }

        $faq->save();

        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Faq::changeStatus($id);
    }

    public function delete($id)
    {
        $faq = Faq::findOrFail($id);
        if ($faq->image) {
            fileManager()->removeFile(getFilePath('faq') . '/' . $faq->image);
        }
        $faq->delete();
        $notify[] = ['success', 'FAQ deleted successfully'];
        return back()->withNotify($notify);
    }
}
