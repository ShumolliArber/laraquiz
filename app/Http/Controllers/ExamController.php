<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Models\ExamSubmission;
use App\Models\Topic;
use App\Support\ExamRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class ExamController extends Controller
{
    public function __construct(public ExamRepository $exams) {}

    public function stats()
    {
        $topics = Topic::query()
            ->withCount('questions')
            ->orderBy('name')
            ->get(['key', 'name']);

        return response()->json([
            'topics_count' => $topics->count(),
            'topics' => $topics->map(fn ($t) => [
                'key' => $t->key,
                'name' => $t->name,
                'questions_count' => $t->questions_count,
            ])->all(),
        ]);
    }

    public function mySubmissions(Request $request)
    {
        $visitorId = $request->cookie('visitor_id');
        if (empty($visitorId)) {
            return response()->json([
                'submissions' => [],
            ]);
        }

        $items = ExamSubmission::query()
            ->where('visitor_id', $visitorId)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get(['topic', 'score', 'total', 'created_at'])
            ->map(function ($s) {
                $percentage = $s->total > 0 ? round(($s->score / $s->total) * 100) : 0;

                return [
                    'topic' => $s->topic,
                    'score' => (int) $s->score,
                    'total' => (int) $s->total,
                    'percentage' => $percentage,
                    'created_at' => $s->created_at ? $s->created_at->toISOString() : null,
                ];
            })
            ->all();

        return response()->json([
            'submissions' => $items,
        ]);
    }

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
        if (! Schema::hasTable('topics')) {
            $topics = [];
        } else {
            $topics = $this->exams->topics();
        }

        return view('exams.index', [
            'topics' => collect($topics),
        ]);
    }

    public function storeTopic(StoreTopicRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Topic::query()->create([
            'key' => $data['key'],
            'name' => $data['name'],
        ]);

        return redirect()->route('exams.index')->with('status', 'Topic created.');
    }

    public function show(string $topic): View|RedirectResponse
    {
        $exam = $this->exams->topic($topic);
        if ($exam === null) {
            return redirect()->route('exams.index');
        }

        $questions = array_slice($exam['questions'] ?? [], 0, 10);

        return view('exams.show', [
            'topicKey' => $topic,
            'topicName' => $exam['name'] ?? ucfirst($topic),
            'questions' => $questions,
        ]);
    }

    public function submit(Request $request, string $topic): View|RedirectResponse|\Illuminate\Http\Response
    {
        $exam = $this->exams->topic($topic);
        if ($exam === null) {
            return redirect()->route('exams.index');
        }

        $questions = array_slice($exam['questions'] ?? [], 0, 10);

        // Build validation rules dynamically: answers.q{index} must be in 0..3
        $rules = [
            'answers' => ['array'],
        ];
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

        // Ensure we have a persistent anonymous visitor id
        $visitorId = $request->cookie('visitor_id');
        if (empty($visitorId)) {
            $visitorId = (string) str()->uuid();
        }

        // Persist submission count row
        ExamSubmission::query()->create([
            'topic' => $topic,
            'visitor_id' => $visitorId,
            'score' => $correct,
            'total' => $total,
        ]);

        // Attach cookie to response to persist for 1 year
        $response = response()->view('exams.result', [
            'topicKey' => $topic,
            'topicName' => $exam['name'] ?? ucfirst($topic),
            'correct' => $correct,
            'total' => $total,
            'percentage' => $percentage,
        ]);
        $response->cookie('visitor_id', $visitorId, 60 * 24 * 365, null, null, false, true, false, 'Lax');

        return $response;
    }
}
