<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Header Menu';
        $menus = Menu::orderBy('order')->get();
        return view('admin.menu.index', compact('pageTitle', 'menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'integer',
        ]);

        Menu::create([
            'name' => $request->name,
            'url' => $request->url,
            'order' => $request->order ?? 0,
            'status' => $request->status ? 1 : 0,
        ]);

        $notify[] = ['success', 'Menu added successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'integer',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'name' => $request->name,
            'url' => $request->url,
            'order' => $request->order ?? 0,
            'status' => $request->status ? 1 : 0,
        ]);

        $notify[] = ['success', 'Menu updated successfully'];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        $notify[] = ['success', 'Menu deleted successfully'];
        return back()->withNotify($notify);
    }
}
