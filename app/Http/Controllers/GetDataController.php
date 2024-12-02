<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Http\Request;

class GetDataController extends Controller
{
    public function getCategories()
    {
        $data = Category::all();

        return response()->json($data);
    }

    public function getGoods(Category $category)
    {
        $data = Goods::where('category_id', $category->id)->get();

        return response()->json($data);
    }
}
