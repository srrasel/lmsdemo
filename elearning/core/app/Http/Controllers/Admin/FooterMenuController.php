<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterMenu;
use Illuminate\Http\Request;

class FooterMenuController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Footer Menu';
        $footerMenus = FooterMenu::orderBy('section_name')->orderBy('order')->get();
        return view('admin.footer_menu.index', compact('pageTitle', 'footerMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'integer',
        ]);

        FooterMenu::create([
            'section_name' => $request->section_name,
            'name' => $request->name,
            'url' => $request->url,
            'order' => $request->order ?? 0,
            'status' => $request->status ? 1 : 0,
        ]);

        $notify[] = ['success', 'Footer menu added successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'section_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'integer',
        ]);

        $footerMenu = FooterMenu::findOrFail($id);
        $footerMenu->update([
            'section_name' => $request->section_name,
            'name' => $request->name,
            'url' => $request->url,
            'order' => $request->order ?? 0,
            'status' => $request->status ? 1 : 0,
        ]);

        $notify[] = ['success', 'Footer menu updated successfully'];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $footerMenu = FooterMenu::findOrFail($id);
        $footerMenu->delete();
        $notify[] = ['success', 'Footer menu deleted successfully'];
        return back()->withNotify($notify);
    }
}
