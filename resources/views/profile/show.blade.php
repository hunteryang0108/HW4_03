<x-layouts.app :title="__('個人檔案')">
    <div class="flex flex-col gap-6">
        <div>
            <flux:heading size="xl">{{ __('個人檔案') }}</flux:heading>
            <flux:subheading>查看與管理您的個人資料</flux:subheading>
        </div>

        <flux:card class="relative">
            <!-- 編輯按鈕 -->
            <a href="{{ route('profile.edit', $user) }}" class="absolute top-6 right-6">
                <flux:button variant="ghost" icon="pencil-square">編輯</flux:button>
            </a>

            <!-- 使用者資訊 -->
            <div class="flex flex-col items-center gap-6 py-8">
                <!-- <img src="{{ $user->profile?->getAvatarUrl() }}" alt="User Avatar"
                    class="w-36 h-36 rounded-full border-4 border-zinc-200 dark:border-zinc-700"> -->
                <img src="{{ $user->profile?->getAvatarUrl() ?? 'data:image/svg+xml;base64,' . base64_encode(generateInitialsAvatar($user->name)) }}" class="w-32 h-32 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700">

                <div class="text-center space-y-4 ">
                    <flux:heading size="3xl" class="font-bold ">{{ $user->name }}</flux:heading>
                    <br>
                    <div class="space-y-2">
                        <p class="text-zinc-700 dark:text-zinc-300">
                            <span class="inline-flex items-center">
                                <flux:icon name="cake" class="w-5 h-5 mr-2" />
                                生日：{{ $user->profile?->birth ?? '未設置' }}
                            </span>
                        </p>
                        <p class="text-zinc-700 dark:text-zinc-300">
                            <span class="inline-flex items-center">
                                <flux:icon name="document-text" class="w-5 h-5 mr-2" />
                                介紹：
                                @if (filled($user->profile?->bio))
                                {{ $user->profile->bio }}
                                @else
                                <span class="text-zinc-400 dark:text-zinc-500">這個人很懶，什麼都沒留下。</span>
                                @endif
                            </span>
                        </p>

                    </div>
                </div>
            </div>


        </flux:card>
        <!-- 歷史貼文 -->
        <flux:card>
            <div class="text-center py-4">
                <flux:heading size="lg" class="mb-6">歷史貼文</flux:heading>

                @if ($user->posts->isEmpty())
                <p class="text-zinc-500 dark:text-zinc-400">你還沒有發佈任何貼文。</p>
                @else
                <div class="space-y-4 max-w-3xl mx-auto">
                    @foreach ($user->posts as $post)
                    <div class="bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg">
                        <h3 class="text-zinc-700 dark:text-zinc-300 font-semibold">{{ $post->title }}</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 text-sm mt-2">{{ Str::limit($post->content, 100) }}</p>
                        <p class="text-zinc-500 dark:text-zinc-400 text-xs mt-2">發佈時間：{{ $post->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </flux:card>
        <!-- 歷史留言區 -->
        <flux:card>
            <div class="text-center py-4">
                <flux:heading size="lg" class="mb-6">歷史留言</flux:heading>

                @if ($comments->isEmpty())
                <p class="text-zinc-500 dark:text-zinc-400">你還沒有留言紀錄。</p>
                @else
                <div class="space-y-4 max-w-3xl mx-auto">
                    @foreach ($comments as $comment)
                    <div class="bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg">
                        <p class="text-zinc-700 dark:text-zinc-300">{{ $comment->content }}</p>
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-2">留言時間：{{ $comment->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </flux:card>
    </div>
</x-layouts.app>