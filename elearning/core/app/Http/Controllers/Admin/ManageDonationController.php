<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;

class ManageDonationController extends Controller
{
    public function index()
    {
        $pageTitle = 'Donations';
        $donations = Donation::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.donation.index', compact('pageTitle', 'donations'));
    }
}
