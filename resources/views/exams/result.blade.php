<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $topicName }} Results</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-[#fff7ed] via-[#fef2f2] to-[#eef2ff] dark:from-[#0a0a0a] dark:via-[#111111] dark:to-[#1a1a1a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="absolute inset-0 opacity-30 pointer-events-none" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,117,15,0.12) 0, transparent 40%), radial-gradient(circle at 80% 30%, rgba(99,102,241,0.12) 0, transparent 35%), radial-gradient(circle at 30% 80%, rgba(34,197,94,0.10) 0, transparent 35%);"></div>
    <div class="relative flex items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-xl mx-auto bg-white/80 dark:bg-[#161615]/80 backdrop-blur shadow-[0_10px_30px_rgba(0,0,0,0.08)] ring-1 ring-black/5 dark:ring-white/10 rounded-xl p-6">
            <div class="mb-4">
                <a class="text-sm text-gray-700 dark:text-gray-300 underline" href="{{ route('exams.index') }}">← Back to exams</a>
            </div>
            <h1 class="text-2xl font-semibold mb-2">{{ $topicName }} Results</h1>
            <p class="mb-6">You scored {{ $correct }}/{{ $total }}. That's {{ $percentage }}%.</p>

            <p class="mt-4">
                <a class="text-[#F53003] underline" href="{{ route('exams.show', $topicKey) }}">Try again</a>
            </p>
        </div>
    </div>
</body>
</html>
