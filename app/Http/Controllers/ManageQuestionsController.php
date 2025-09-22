<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Support\ExamRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ManageQuestionsController extends Controller
{
    public function __construct(public ExamRepository $exams) {}

    public function index(string $topic): View|RedirectResponse
    {
        $exam = $this->exams->topic($topic);
        if ($exam === null) {
            return redirect()->route('exams.index');
        }

        return view('exams.manage', [
            'topicKey' => $topic,
            'topicName' => $exam['name'],
            'questions' => $exam['questions'],
        ]);
    }

    public function store(StoreQuestionRequest $request, string $topic): RedirectResponse
    {
        $exam = $this->exams->topic($topic);
        if ($exam === null) {
            return redirect()->route('exams.index');
        }

        $data = $request->validated();
        $question = [
            'title' => $data['title'],
            'description' => $data['description'],
            'choices' => array_values($data['choices']),
            'answer' => (int) $data['answer'],
        ];
        $this->exams->addQuestion($topic, $question);

        return redirect()->route('exams.manage', $topic)->with('status', 'Question added.');
    }

    public function edit(string $topic, int $index): View|RedirectResponse
    {
        $exam = $this->exams->topic($topic);
        if ($exam === null) {
            return redirect()->route('exams.index');
        }
        $questions = $exam['questions'];
        if (! array_key_exists($index, $questions)) {
            return redirect()->route('exams.manage', $topic);
        }

        return view('exams.edit', [
            'topicKey' => $topic,
            'topicName' => $exam['name'],
            'index' => $index,
            'question' => $questions[$index],
        ]);
    }

    public function update(UpdateQuestionRequest $request, string $topic, int $index): RedirectResponse
    {
        $exam = $this->exams->topic($topic);
        if ($exam === null) {
            return redirect()->route('exams.index');
        }

        $data = $request->validated();
        $question = [
            'title' => $data['title'],
            'description' => $data['description'],
            'choices' => array_values($data['choices']),
            'answer' => (int) $data['answer'],
        ];
        $this->exams->updateQuestion($topic, $index, $question);

        return redirect()->route('exams.manage', $topic)->with('status', 'Question updated.');
    }

    public function destroy(string $topic, int $index): RedirectResponse
    {
        $exam = $this->exams->topic($topic);
        if ($exam === null) {
            return redirect()->route('exams.index');
        }

        $this->exams->deleteQuestion($topic, $index);

        return redirect()->route('exams.manage', $topic)->with('status', 'Question deleted.');
    }
}
