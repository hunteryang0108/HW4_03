<x-layouts.app :title="__('個人檔案')">
    <div class="flex flex-col gap-6">
        <div>
            <flux:heading size="xl">{{ __('個人檔案') }}</flux:heading>
            <flux:subheading>編輯您的個人資料</flux:subheading>
        </div>

        <flux:card>
            <form method="POST" action="{{ route('profile.update', $user) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- 頭像上傳 -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold">目前頭像</h2>
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <img id="preview" src="{{ $user->profile?->getAvatarUrl() }}"
                            class="w-10 h-10 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700">
                        <label class="cursor-pointer bg-zinc-200 dark:bg-zinc-700 px-4 py-2 rounded-lg">
                            選擇檔案
                            <input type="file" name="avatar" class="hidden" id="avatarInput">
                        </label>
                    </div>
                </div>

                <!-- 頭像預覽 JS -->
                <script>
                    document.getElementById('avatarInput').addEventListener('change', function(event) {
                        const [file] = event.target.files;
                        if (file) {
                            document.getElementById('preview').src = URL.createObjectURL(file);
                        }
                    });
                </script>

                <!-- 生日 -->
                <div class="space-y-4">
                    <flux:heading size="lg">生日</flux:heading>
                    <flux:input
                        type="date"
                        name="birth"
                        id="birth"
                        value="{{ old('birth', $user->profile?->birth?->format('Y-m-d')) }}" />
                </div>

                <!-- 自我介紹 -->
                <div class="space-y-4">
                    <flux:heading size="lg">自我介紹</flux:heading>
                    <flux:textarea
                        name="bio"
                        id="bio"
                        rows="5">{{ old('bio', $user->profile?->bio) }}</flux:textarea>
                </div>

                <!-- 提交按鈕 -->
                <div class="flex justify-end pt-4 space-x-2">
                    <flux:button type="submit">儲存變更</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts.app>