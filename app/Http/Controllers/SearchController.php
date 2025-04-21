<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // 如果搜索欄為空，重定向回首頁
        if (empty($query)) {
            return redirect()->route('posts.index');
        }
        
        // 儲存搜尋紀錄（使用 session）
        $searchHistory = $request->session()->get('search_history', []);
        
        // 檢查是否已存在相同搜尋詞，如果有則先移除
        $searchHistory = array_filter($searchHistory, function($item) use ($query) {
            return $item !== $query;
        });
        
        // 將新的搜尋詞添加到開頭
        array_unshift($searchHistory, $query);
        
        // 只保留最近3個搜尋紀錄
        $searchHistory = array_slice($searchHistory, 0, 3);
        
        // 更新 session
        $request->session()->put('search_history', $searchHistory);
        
        // 取得高級搜尋選項
        $inTitle = $request->has('in_title');
        $inContent = $request->has('in_content');
        $inUser = $request->has('in_user');
        
        // 如果都沒有選擇，預設全部搜尋
        if (!$inTitle && !$inContent && !$inUser) {
            $inTitle = $inContent = $inUser = true;
        }
        
        // 執行搜尋 - 根據選擇的範圍
        $posts = Post::where('deleted', false)
                     ->where(function($q) use ($query, $inTitle, $inContent, $inUser) {
                         if ($inTitle) {
                             $q->orWhere('title', 'like', '%' . $query . '%');
                         }
                         if ($inContent) {
                             $q->orWhere('content', 'like', '%' . $query . '%');
                         }
                         if ($inUser) {
                             $q->orWhereHas('user', function($subQuery) use ($query) {
                                 $subQuery->where('name', 'like', '%' . $query . '%');
                             });
                         }
                     })
                     ->with(['user', 'tags', 'comments'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
        
        // 獲取熱門標籤
        $tags = Tag::orderBy('posts_count', 'desc')->take(10)->get();
        
        // 傳遞高級搜尋選項到視圖
        $searchOptions = [
            'in_title' => $inTitle,
            'in_content' => $inContent,
            'in_user' => $inUser
        ];
        
        return view('search.results', compact('posts', 'query', 'tags', 'searchHistory', 'searchOptions'));
    }
    
    public function suggestions(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            // 如果沒有搜尋詞，返回熱門討論的前三篇文章
            $posts = Post::where('deleted', false)
                         ->withCount('comments')
                         ->orderBy('comments_count', 'desc')
                         ->take(3)
                         ->get(['id', 'title']);
                         
            return response()->json($posts);
        } else {
            // 獲取搜尋選項
            $inTitle = $request->has('in_title');
            $inContent = $request->has('in_content');
            $inUser = $request->has('in_user');
            
            // 如果都沒有選擇，預設全部搜尋
            if (!$inTitle && !$inContent && !$inUser) {
                $inTitle = $inContent = $inUser = true;
            }
            
            // 根據搜尋詞返回建議 - 符合高級選項
            $postsQuery = Post::where('deleted', false)
                             ->where(function($q) use ($query, $inTitle, $inContent, $inUser) {
                                 if ($inTitle) {
                                     $q->orWhere('title', 'like', '%' . $query . '%');
                                 }
                                 if ($inContent) {
                                     $q->orWhere('content', 'like', '%' . $query . '%');
                                 }
                                 if ($inUser) {
                                     $q->orWhereHas('user', function($subQuery) use ($query) {
                                         $subQuery->where('name', 'like', '%' . $query . '%');
                                     });
                                 }
                             })
                             ->with('user')
                             ->take(5);
            
            $posts = $postsQuery->get();
            
            // 添加使用者提示標記
            foreach ($posts as $post) {
                if ($inUser && stripos($post->title, $query) === false && 
                    stripos($post->user->name, $query) !== false) {
                    $post->title = $post->title . ' (作者: ' . $post->user->name . ')';
                }
            }
            
            return response()->json($posts);
        }
    }
    
    public function clearHistory(Request $request)
    {
        $request->session()->forget('search_history');
        return redirect()->back()->with('message', '搜尋歷史已清除');
    }
}