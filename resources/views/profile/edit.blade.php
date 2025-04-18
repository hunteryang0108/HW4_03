<x-layouts.app :title="__('Profile')">
    <div class="flex justify-center items-center min-h-screen bg-gray-50">
        <div class="w-full max-w-4xl mx-auto px-6 py-12">
            <div class="bg-white rounded-3xl shadow-xl px-12 py-10 space-y-10">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-blue-600">編輯個人資料</h1>
                </div>

                <form method="POST" action="{{ route('profile.update', $user) }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- 頭像上傳 -->
                    <div class="space-y-3">
                        <label class="block text-lg font-semibold text-gray-700">目前頭像</label>
                        <div class="flex flex-col sm:flex-row items-center gap-6">
                            <img src="{{ $user->profile?->getAvatarUrl() }}" class="w-32 h-32 rounded-full object-cover border shadow">
                            <label class="inline-block cursor-pointer bg-gray-100 text-gray-700 px-4 py-2 rounded border border-gray-300 shadow-sm hover:bg-gray-200 transition">
                                選擇檔案
                                <input type="file" name="avatar" class="hidden">
                            </label>
                        </div>
                    </div>

                    <!-- 生日 -->
                    <div class="space-y-2">
                        <label for="birth" class="block text-lg font-semibold text-gray-700">生日</label>
                        <input type="date" name="birth" id="birth"
                            value="{{ old('birth', $user->profile?->birth?->format('Y-m-d')) }}"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- 自我介紹 -->
                    <div class="space-y-2">
                        <label for="bio" class="block text-lg font-semibold text-gray-700">自我介紹</label>
                        <textarea name="bio" id="bio" rows="5"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio', $user->profile?->bio) }}</textarea>
                    </div>

                    <!-- 提交按鈕 -->
                    <div class="text-right pt-4">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-md shadow hover:bg-blue-700 transition">
                            儲存變更
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
