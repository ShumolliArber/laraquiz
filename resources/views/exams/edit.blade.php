<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question - {{ $topicName }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-[#fff7ed] via-[#fef2f2] to-[#eef2ff] dark:from-[#0a0a0a] dark:via-[#111111] dark:to-[#1a1a1a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="absolute inset-0 opacity-30 pointer-events-none" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,117,15,0.12) 0, transparent 40%), radial-gradient(circle at 80% 30%, rgba(99,102,241,0.12) 0, transparent 35%), radial-gradient(circle at 30% 80%, rgba(34,197,94,0.10) 0, transparent 35%);"></div>
    <div class="relative flex items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-2xl mx-auto bg-white/80 dark:bg-[#161615]/80 backdrop-blur shadow-[0_10px_30px_rgba(0,0,0,0.08)] ring-1 ring-black/5 dark:ring-white/10 rounded-xl p-6">
            <div class="mb-4">
                <a class="text-sm text-gray-700 dark:text-gray-300 underline" href="{{ route('exams.manage', $topicKey) }}">← Back to manage</a>
            </div>
            <h1 class="text-2xl font-semibold mb-4">Edit Question #{{ $index + 1 }} - {{ $topicName }}</h1>

            <form action="{{ route('exams.questions.update', [$topicKey, $index]) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title', $question['title'] ?? '') }}" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">
                    @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">{{ old('description', $question['description'] ?? '') }}</textarea>
                    @error('description')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm mb-1">Choices (4)</label>
                    <div class="grid grid-cols-1 gap-2">
                        @for ($i = 0; $i < 4; $i++)
                            <input type="text" name="choices[{{ $i }}]" value="{{ old('choices.'.$i, $question['choices'][$i] ?? '') }}" placeholder="Choice #{{ $i + 1 }}" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">
                        @endfor
                    </div>
                    @error('choices')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    @foreach (range(0,3) as $i)
                        @error('choices.'.$i)<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    @endforeach
                </div>
                <div>
                    <label class="block text-sm mb-1">Correct Answer Index (0-3)</label>
                    <input type="number" name="answer" min="0" max="3" value="{{ old('answer', (int)($question['answer'] ?? 0)) }}" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">
                    @error('answer')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
