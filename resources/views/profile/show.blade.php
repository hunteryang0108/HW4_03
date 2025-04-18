<x-layouts.app :title="__('Profile')">
    <div class="flex justify-center items-center min-h-screen bg-gray-50">
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl px-16 py-16 space-y-14 relative">
            <!-- 編輯 icon -->
            <a href="{{ route('profile.edit', $user) }}" class="absolute top-8 right-8 text-gray-500 hover:text-blue-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.232 5.232l3.536 3.536M9 13l6-6 3.536 3.536-6 6H9v-3.536z" />
                </svg>
            </a>

            <!-- 使用者資訊 -->
            <div class="flex flex-col items-center gap-6">
                <img src="{{ $user->profile?->getAvatarUrl() }}" alt="User Avatar"
                    class="w-36 h-36 rounded-full border-4 border-gray-300">

                <div class="text-center space-y-2">
                    <flux:heading size="xl" class="text-3xl font-semibold">{{ $user->name }}</flux:heading>
                    <p class="text-gray-700 text-lg">🎂 生日：{{ $user->profile?->birth ?? '未設置' }}</p>
                    <p class="text-gray-700 text-lg">📜 自我介紹：{{ $user->profile?->bio ?? '這個人很懶，什麼都沒留下。' }}</p>
                </div>
            </div>

            <!-- 資料說明 -->
            <div class="bg-gray-100 p-8 rounded-xl shadow text-center">
                <p class="text-gray-500 text-lg">這是你的個人資料，你可以在這裡編輯或更新你的資料。</p>
            </div>

            <!-- 歷史留言區 -->
            <div class="bg-gray-50 p-10 rounded-xl border border-dashed border-gray-300 text-center max-w-3xl mx-auto">
                <h3 class="text-3xl font-bold text-gray-700 mb-4">歷史留言</h3>
                <p class="text-gray-500 text-base">這裡將會顯示你過去的留言紀錄</p>
            </div>
        </div>
    </div>
</x-layouts.app>