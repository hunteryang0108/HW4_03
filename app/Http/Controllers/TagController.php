<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // 獲取所有標籤（API用）
    public function index()
    {
        $tags = Tag::orderBy('posts_count', 'desc')
              ->take(50)
              ->get(['id', 'name', 'slug']);
        return response()->json($tags);
    }
    
    // 搜尋標籤（自動完成用）
    public function search(Request $request)
    {
        $query = $request->get('q');
        $tags = Tag::where('name', 'like', "%{$query}%")
                  ->orderBy('posts_count', 'desc')
                  ->take(10)
                  ->get(['id', 'name', 'slug']);
                  
        return response()->json($tags);
    }
}