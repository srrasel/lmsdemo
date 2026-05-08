<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->get();
        $notify[] = 'All FAQs';
        return responseSuccess('faqs', $notify, ['faqs' => $faqs]);
    }
}
