<?php

Route::get('/tags', function () {
    $tags = \App\Models\Tag::all()->map(function($tag) {
        return [
            'value' => $tag->id,
            'text' => $tag->name,
            'color' => $tag->color
        ];
    });
    
    return response()->json($tags);
})->name('api.tags');