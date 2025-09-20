<?php

namespace App\Http\Controllers;

use App\Models\ExamSubmission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ExamController extends Controller
{
    public function submissionsCount(Request $request)
    {
        $topic = $request->query('topic');
        $query = ExamSubmission::query();
        if ($topic) {
            $query->where('topic', $topic);
        }
        $count = $query->count();

        return response()->json([
            'count' => $count,
            'topic' => $topic,
        ]);
    }

    public function index(): View
    {
        $topics = collect(config('exams.topics', []))
            ->map(fn ($t, $key) => ['key' => $key, 'name' => $t['name'] ?? ucfirst($key)])
            ->values();

        return view('exams.index', [
            'topics' => $topics,
        ]);
    }

    public function show(string $topic): View|RedirectResponse
    {
        $topics = config('exams.topics', []);
        if (! array_key_exists($topic, $topics)) {
            return redirect()->route('exams.index');
        }

        $exam = $topics[$topic];
        $questions = $exam['questions'] ?? [];
        $questions = array_slice($questions, 0, 10);

        return view('exams.show', [
            'topicKey' => $topic,
            'topicName' => $exam['name'] ?? ucfirst($topic),
            'questions' => $questions,
        ]);
    }

    public function submit(Request $request, string $topic): View|RedirectResponse
    {
        $topics = config('exams.topics', []);
        if (! array_key_exists($topic, $topics)) {
            return redirect()->route('exams.index');
        }

        $exam = $topics[$topic];
        $questions = array_slice($exam['questions'] ?? [], 0, 10);

        // Build validation rules dynamically: answers.q{index} must be in 0..3
        $rules = [];
        foreach ($questions as $i => $_) {
            $rules["answers.$i"] = ['nullable', 'integer', 'min:0', 'max:3'];
        }
        $data = $request->validate($rules);

        $answers = $data['answers'] ?? [];

        $correct = 0;
        foreach ($questions as $index => $question) {
            $selected = (int) Arr::get($answers, $index);
            if (isset($question['answer']) && $selected === (int) $question['answer']) {
                $correct++;
            }
        }

        $total = count($questions);
        $percentage = $total > 0 ? round(($correct / $total) * 100) : 0;

        // Persist submission count row
        ExamSubmission::query()->create([
            'topic' => $topic,
            'score' => $correct,
            'total' => $total,
        ]);

        return view('exams.result', [
            'topicKey' => $topic,
            'topicName' => $exam['name'] ?? ucfirst($topic),
            'correct' => $correct,
            'total' => $total,
            'percentage' => $percentage,
        ]);
    }
}
