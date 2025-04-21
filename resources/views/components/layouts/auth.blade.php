<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
                <div class="absolute inset-0 flex items-center justify-center" style="background-color: #141414;">
                    <img src="{{ Vite::asset('resources/images/logo.jpg') }}" class="w-1/2 h-auto object-contain">
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto my-5 flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
