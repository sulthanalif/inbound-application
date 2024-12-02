<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest()->get();

        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $category = new Category();
                $category->name = $request->name;
                $category->save();
            });

            Alert::success('Hore!', 'Category Created Successfully');
            return redirect()->route('categories.index');
        } catch (\Throwable $th) {
            Alert::error('Category Creation Failed', $th->getMessage());
            return redirect()->route('categories.index');
        }
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,' . $category->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $category) {
                $category->name = $request->name;
                $category->save();
            });

            Alert::success('Hore!', 'Category Updated Successfully');
            return redirect()->route('categories.index');
        } catch (\Throwable $th) {
            Alert::error('Category Update Failed', $th->getMessage());
            return redirect()->route('categories.index');
        }
    }

    public function destroy(Category $category)
    {
        try {
            DB::transaction(function () use ($category) {
                $category->delete();
            });

            Alert::success('Hore!', 'Category Deletion Successfully');

            return back();
        } catch (\Throwable $th) {
            Alert::error('Category deletion failed', $th->getMessage());
            return redirect()->route('categories.index');
        }
    }
}
