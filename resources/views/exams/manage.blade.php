<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage {{ $topicName }} Questions</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-[#fff7ed] via-[#fef2f2] to-[#eef2ff] dark:from-[#0a0a0a] dark:via-[#111111] dark:to-[#1a1a1a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="absolute inset-0 opacity-30 pointer-events-none" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,117,15,0.12) 0, transparent 40%), radial-gradient(circle at 80% 30%, rgba(99,102,241,0.12) 0, transparent 35%), radial-gradient(circle at 30% 80%, rgba(34,197,94,0.10) 0, transparent 35%);"></div>
    <div class="relative flex items-start md:items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-4xl mx-auto bg-white/80 dark:bg-[#161615]/80 backdrop-blur shadow-[0_10px_30px_rgba(0,0,0,0.08)] ring-1 ring-black/5 dark:ring-white/10 rounded-xl p-5 sm:p-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <a class="text-sm text-gray-700 dark:text-gray-300 underline" href="{{ route('exams.show', $topicKey) }}">← Back to exam</a>
                    <h1 class="text-2xl font-semibold">Manage {{ $topicName }} Questions</h1>
                </div>
                <a class="text-sm text-[#F53003] underline" href="{{ route('exams.index') }}">All exams</a>
            </div>

            @if (session('status'))
                <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded px-3 py-2">{{ session('status') }}</div>
            @endif

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h2 class="font-medium mb-2">Existing Questions ({{ count($questions) }})</h2>
                    <ol class="space-y-3">
                        @foreach ($questions as $i => $q)
                            <li class="border border-black/10 dark:border-white/10 p-4 rounded-lg bg-white/70 dark:bg-[#0f0f0f]/60">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="font-medium">Q{{ $i + 1 }}. {{ $q['title'] }}</h3>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">{{ $q['description'] }}</p>
                                        <ul class="text-sm list-disc pl-5">
                                            @foreach ($q['choices'] as $ci => $choice)
                                                <li @class(['font-semibold'=> $ci === (int)($q['answer'] ?? -1)])>
                                                    {{ $choice }}
                                                    @if ($ci === (int)($q['answer'] ?? -1))
                                                        <span class="text-green-600">(correct)</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="shrink-0 flex flex-col gap-2">
                                        <a href="{{ route('exams.questions.edit', [$topicKey, $i]) }}" class="px-3 py-1 rounded bg-blue-600 text-white text-sm">Edit</a>
                                        <form action="{{ route('exams.questions.destroy', [$topicKey, $i]) }}" method="POST" onsubmit="return confirm('Delete this question?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        @if (empty($questions))
                            <li class="text-sm text-gray-600">No questions yet.</li>
                        @endif
                    </ol>
                </div>
                <div>
                    <h2 class="font-medium mb-2">Add New Question</h2>
                    <form action="{{ route('exams.questions.store', $topicKey) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm mb-1">Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">
                            @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Description</label>
                            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">{{ old('description') }}</textarea>
                            @error('description')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Choices (4)</label>
                            <div class="grid grid-cols-1 gap-2">
                                @for ($i = 0; $i < 4; $i++)
                                    <input type="text" name="choices[{{ $i }}]" value="{{ old('choices.'.$i) }}" placeholder="Choice #{{ $i + 1 }}" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">
                                @endfor
                            </div>
                            @error('choices')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                            @foreach (range(0,3) as $i)
                                @error('choices.'.$i)<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                            @endforeach
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Correct Answer Index (0-3)</label>
                            <input type="number" name="answer" min="0" max="3" value="{{ old('answer', 0) }}" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">
                            @error('answer')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <button type="submit" class="bg-[#F53003] hover:bg-[#d82a02] text-white px-4 py-2 rounded-md shadow">Add Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
