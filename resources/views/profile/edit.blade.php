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
                    <flux:heading size="lg">目前頭像</flux:heading>
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <img src="{{ $user->profile?->getAvatarUrl() }}" class="w-32 h-32 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700">
                        <flux:button as="label" variant="secondary">
                            選擇檔案
                            <input type="file" name="avatar" class="hidden">
                        </flux:button>
                    </div>
                </div>

                <!-- 生日 -->
                <div class="space-y-4">
                    <flux:heading size="lg">生日</flux:heading>
                    <flux:input
                        type="date"
                        name="birth"
                        id="birth"
                        value="{{ old('birth', $user->profile?->birth?->format('Y-m-d')) }}"
                    />
                </div>

                <!-- 自我介紹 -->
                <div class="space-y-4">
                    <flux:heading size="lg">自我介紹</flux:heading>
                    <flux:textarea
                        name="bio"
                        id="bio"
                        rows="5"
                    >{{ old('bio', $user->profile?->bio) }}</flux:textarea>
                </div>

                <!-- 提交按鈕 -->
                <div class="flex justify-end pt-4">
                    <flux:button type="submit">儲存變更</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts.app>