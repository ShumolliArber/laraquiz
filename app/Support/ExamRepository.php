<?php

namespace App\Support;

use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Arr;

class ExamRepository
{
    /**
     * Get all topics with names (from config) without heavy questions payload.
     *
     * @return array<int, array{key:string,name:string}>
     */
    public function topics(): array
    {
        // Ensure topics exist in DB (bootstrap from config on first call)
        $this->bootstrapTopics();

        return Topic::query()
            ->orderBy('name')
            ->get(['key', 'name'])
            ->map(fn (Topic $t) => ['key' => $t->key, 'name' => $t->name])
            ->all();
    }

    /**
     * Get a topic by key with questions. Questions can be overridden by storage JSON.
     *
     * @return array{name:string,questions:array<int, array{title:string,description:string,choices:array<int,string>,answer:int}>}|null
     */
    public function topic(string $key): ?array
    {
        $this->bootstrapTopics();

        $topic = Topic::query()->where('key', $key)->first();
        if (! $topic) {
            return null;
        }

        // If topic has no questions yet, bootstrap from config once
        if (! $topic->questions()->exists()) {
            $this->bootstrapQuestionsFromConfig($topic);
        }

        $questions = $topic->questions()->orderBy('position')->orderBy('id')->get();

        return [
            'name' => $topic->name,
            'questions' => $questions->map(function (Question $q): array {
                return [
                    'title' => $q->title,
                    'description' => $q->description,
                    'choices' => $q->choices,
                    'answer' => (int) $q->answer,
                ];
            })->all(),
        ];
    }

    /**
     * Replace all questions for a topic using DB.
     *
     * @param  array<int, array{title:string,description:string,choices:array<int,string>,answer:int}>  $questions
     */
    public function saveQuestions(string $key, array $questions): void
    {
        $topic = Topic::query()->where('key', $key)->first();
        if (! $topic) {
            return;
        }

        // Delete existing and re-insert in order
        $topic->questions()->delete();
        foreach (array_values($questions) as $i => $q) {
            $this->createQuestion($topic, $q, $i);
        }
    }

    /**
     * Add a question to the end of list and persist.
     *
     * @param  array{title:string,description:string,choices:array<int,string>,answer:int}  $question
     */
    public function addQuestion(string $key, array $question): void
    {
        $topic = Topic::query()->where('key', $key)->first();
        if (! $topic) {
            return;
        }
        $position = (int) ($topic->questions()->max('position') ?? -1) + 1;
        $this->createQuestion($topic, $question, $position);
    }

    /**
     * Update a question at index and persist.
     *
     * @param  array{title:string,description:string,choices:array<int,string>,answer:int}  $question
     */
    public function updateQuestion(string $key, int $index, array $question): void
    {
        $topic = Topic::query()->where('key', $key)->first();
        if (! $topic) {
            return;
        }
        $q = $topic->questions()->orderBy('position')->orderBy('id')->skip($index)->first();
        if (! $q) {
            return;
        }
        $q->update([
            'title' => $question['title'],
            'description' => $question['description'],
            'choice_0' => $question['choices'][0] ?? '',
            'choice_1' => $question['choices'][1] ?? '',
            'choice_2' => $question['choices'][2] ?? '',
            'choice_3' => $question['choices'][3] ?? '',
            'answer' => (int) $question['answer'],
        ]);
    }

    /**
     * Delete a question at index and persist.
     */
    public function deleteQuestion(string $key, int $index): void
    {
        $topic = Topic::query()->where('key', $key)->first();
        if (! $topic) {
            return;
        }
        $q = $topic->questions()->orderBy('position')->orderBy('id')->skip($index)->first();
        if (! $q) {
            return;
        }
        $q->delete();

        // Recompute positions to keep them contiguous
        $remaining = $topic->questions()->orderBy('position')->orderBy('id')->get();
        foreach ($remaining as $i => $row) {
            if ($row->position !== $i) {
                $row->position = $i;
                $row->save();
            }
        }
    }

    /**
     * Ensure topics table contains topics from config.
     */
    protected function bootstrapTopics(): void
    {
        // No-op: topics are now fully managed via DB/UI.
    }

    protected function bootstrapQuestionsFromConfig(Topic $topic): void
    {
        // No-op: questions are now fully managed via DB/UI.
    }

    /**
     * @param  array{title:string,description:string,choices:array<int,string>,answer:int}  $q
     */
    protected function createQuestion(Topic $topic, array $q, int $position): Question
    {
        return $topic->questions()->create([
            'title' => $q['title'],
            'description' => $q['description'],
            'choice_0' => $q['choices'][0] ?? '',
            'choice_1' => $q['choices'][1] ?? '',
            'choice_2' => $q['choices'][2] ?? '',
            'choice_3' => $q['choices'][3] ?? '',
            'answer' => (int) $q['answer'],
            'position' => $position,
        ]);
    }
}
