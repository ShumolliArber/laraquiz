<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laraqiuz - A simple app for quizes boilt with AI</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-[#fff7ed] via-[#fef2f2] to-[#eef2ff] dark:from-[#0a0a0a] dark:via-[#111111] dark:to-[#1a1a1a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="absolute inset-0 opacity-30 pointer-events-none" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,117,15,0.12) 0, transparent 40%), radial-gradient(circle at 80% 30%, rgba(99,102,241,0.12) 0, transparent 35%), radial-gradient(circle at 30% 80%, rgba(34,197,94,0.10) 0, transparent 35%);"></div>
    <div class="relative flex items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-xl mx-auto bg-white/80 dark:bg-[#161615]/80 backdrop-blur shadow-[0_10px_30px_rgba(0,0,0,0.08)] ring-1 ring-black/5 dark:ring-white/10 rounded-xl p-6">
            <h1 class="text-2xl font-semibold mb-4">Choose an Exam</h1>
            @if (session('status'))
                <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded px-3 py-2">{{ session('status') }}</div>
            @endif

            <form action="{{ route('topics.store') }}" method="POST" class="mb-6 space-y-3">
                @csrf
                <h2 class="font-medium">Create a new Topic</h2>
                <div>
                    <label class="block text-sm mb-1">Key (slug)</label>
                    <input type="text" name="key" value="{{ old('key') }}" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">
                    @error('key')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 bg-white/70 dark:bg-[#0f0f0f]/60">
                    @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <button type="submit" class="bg-[#F53003] hover:bg-[#d82a02] text-white px-4 py-2 rounded-md shadow">Add Topic</button>
                </div>
            </form>

            <h2 class="text-xl font-semibold mb-3">Available Exams</h2>
            <ul class="space-y-3">
                @forelse ($topics as $topic)
                    <li>
                        <a class="inline-block w-full text-left px-4 py-2 rounded-md bg-white/70 dark:bg-[#0f0f0f]/60 border border-black/10 dark:border-white/10 hover:bg-white dark:hover:bg-[#181818] transition underline decoration-transparent hover:decoration-inherit text-[#F53003]" href="{{ route('exams.show', $topic['key']) }}">
                            {{ $topic['name'] }}
                        </a>
                    </li>
                @empty
                    <li class="text-gray-600">No topics yet. Create one above.</li>
                @endforelse
            </ul>
        </div>
    </div>
</body>
</html>
