<x-layouts.app :title="__('編輯留言')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center mb-6">
            <flux:button href="{{ route('posts.show', $comment->post_id) }}" variant="outline">
                <flux:icon.arrow-left class="mr-2" /> 返回文章
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 shadow rounded-lg p-6">
            <flux:heading size="xl" class="mb-6">編輯留言</flux:heading>

            <form action="{{ route('comments.update', $comment) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <flux:textarea 
                        name="content" 
                        rows="5" 
                        required
                    >{{ old('content', $comment->content) }}</flux:textarea>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <flux:button href="{{ route('posts.show', $comment->post_id) }}" variant="outline">
                        取消
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        更新留言
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>