<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // 顯示所有文章
    public function index(Request $request)
    {
        $tag = $request->query('tag');
        $sort = $request->query('sort', 'latest'); // 預設為最新

        $query = Post::where('deleted', false)
                     ->with(['user', 'tags']);

        // 依照排序選項
        switch ($sort) {
            case 'commented':
                $query->withCount('comments')
                      ->orderBy('comments_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // 如果有標籤參數，則過濾文章
        if ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('slug', $tag);
            });
        }

        $posts = $query->paginate(10);
        $allTags = Tag::orderBy('posts_count', 'desc')->take(20)->get();

        return view('posts.index', [
            'posts' => $posts,
            'tags' => $allTags,
            'currentTag' => $tag,
            'currentSort' => $sort
        ]);
    }

    // 顯示創建文章表單
    public function create()
    {
        $tags = Tag::orderBy('name')->get();
        return view('posts.create', compact('tags'));
    }

    // 儲存新文章
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $post = new Post([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('image')) {
            $post->image = file_get_contents($request->file('image')->getRealPath());
        }

        $post->save();

        if (isset($validated['tags'])) {
            $tagIds = $this->processTagsInput($validated['tags']);
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('posts.show', $post)->with('success', '文章發佈成功！');
    }

    // 顯示單篇文章
    public function show(Post $post)
    {
        if ($post->deleted) {
            abort(404);
        }

        $post->load(['user', 'tags', 'comments.user']);

        return view('posts.show', compact('post'));
    }

    // 顯示編輯文章表單
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $allTags = Tag::orderBy('name')->get();
        $postTags = $post->tags->pluck('name')->implode(',');

        $postTagsJson = $post->tags->map(function ($tag) {
            return [
                'value' => $tag->id,
                'text' => $tag->name
            ];
        })->toJson();

        return view('posts.edit', compact('post', 'allTags', 'postTags', 'postTagsJson'));
    }

    // 更新文章
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $post->title = $validated['title'];
        $post->content = $validated['content'];

        if ($request->hasFile('image')) {
            $post->image = file_get_contents($request->file('image')->getRealPath());
        }

        $post->save();

        if (isset($validated['tags'])) {
            $tagIds = $this->processTagsInput($validated['tags']);
            $post->tags()->sync($tagIds);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('posts.show', $post)->with('success', '文章更新成功！');
    }

    // 刪除文章
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->deleted = true;
        $post->save();

        return redirect()->route('posts.index')->with('success', '文章已成功刪除');
    }

    // 標籤解析輔助方法
    private function processTagsInput($tagsInput)
    {
        $tagIds = [];

        if (!empty($tagsInput)) {
            $decodedTags = json_decode($tagsInput, true);

            if (is_array($decodedTags)) {
                foreach ($decodedTags as $tag) {
                    if (isset($tag['value'])) {
                        $tagIds[] = $tag['value'];
                    }
                }
            } else {
                $tagNames = explode(',', $tagsInput);
                foreach ($tagNames as $name) {
                    $tag = Tag::firstOrCreate(['name' => trim($name)]);
                    $tagIds[] = $tag->id;
                }
            }
        }

        return $tagIds;
    }
}
