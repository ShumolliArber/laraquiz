<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $topicName }} Exam</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-[#fff7ed] via-[#fef2f2] to-[#eef2ff] dark:from-[#0a0a0a] dark:via-[#111111] dark:to-[#1a1a1a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="absolute inset-0 opacity-30 pointer-events-none" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,117,15,0.12) 0, transparent 40%), radial-gradient(circle at 80% 30%, rgba(99,102,241,0.12) 0, transparent 35%), radial-gradient(circle at 30% 80%, rgba(34,197,94,0.10) 0, transparent 35%);"></div>
    <div class="relative flex items-start md:items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-3xl mx-auto bg-white/80 dark:bg-[#161615]/80 backdrop-blur shadow-[0_10px_30px_rgba(0,0,0,0.08)] ring-1 ring-black/5 dark:ring-white/10 rounded-xl p-5 sm:p-8">
            <div class="mb-4 flex items-center justify-between">
                <a class="text-sm text-gray-700 dark:text-gray-300 underline" href="{{ route('exams.index') }}">← Back</a>
                <a class="text-sm text-[#F53003] underline" href="{{ route('exams.manage', $topicKey) }}">Manage questions</a>
            </div>
            <h1 class="text-2xl font-semibold mb-2">{{ $topicName }} Exam</h1>
            <p class="mb-6 text-gray-700 dark:text-gray-300">Answer all questions and submit to see your score.</p>

            <form action="{{ route('exams.submit', $topicKey) }}" method="POST" class="space-y-6">
                @csrf
                @foreach ($questions as $i => $q)
                    <div class="border border-black/10 dark:border-white/10 p-4 sm:p-5 rounded-lg bg-white/70 dark:bg-[#0f0f0f]/60">
                        <h2 class="font-medium">Q{{ $i + 1 }}. {{ $q['title'] }}</h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-3">{{ $q['description'] }}</p>
                        @error('answers.' . $i)
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach ($q['choices'] as $ci => $choice)
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="answers[{{ $i }}]" value="{{ $ci }}" @checked(old('answers.' . $i) == $ci)>
                                    <span>{{ $choice }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div class="pt-2">
                    <button type="submit" class="bg-[#F53003] hover:bg-[#d82a02] text-white px-4 py-2 rounded-md shadow">Submit Exam</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
