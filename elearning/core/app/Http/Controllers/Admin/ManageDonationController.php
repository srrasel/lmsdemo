<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class ManageDonationController extends Controller
{
    public function index()
    {
        $pageTitle = 'Donations';
        if (!Schema::hasTable('donations')) {
            $donations = new LengthAwarePaginator([], 0, getPaginate(), 1, ['path' => request()->url(), 'query' => request()->query()]);
            $notify[] = ['error', 'Donations table is missing. Please run database migrations.'];
            session()->flash('notify', $notify);
        } else {
            $donations = Donation::orderBy('id', 'desc')->paginate(getPaginate());
        }
        return view('admin.donation.index', compact('pageTitle', 'donations'));
    }
}
