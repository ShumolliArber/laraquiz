<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ExamRepository
{
    /**
     * Get all topics with names (from config) without heavy questions payload.
     *
     * @return array<int, array{key:string,name:string}>
     */
    public function topics(): array
    {
        $topics = config('exams.topics', []);

        $list = [];
        foreach ($topics as $key => $data) {
            $list[] = [
                'key' => (string) $key,
                'name' => (string) ($data['name'] ?? ucfirst((string) $key)),
            ];
        }

        return $list;
    }

    /**
     * Get a topic by key with questions. Questions can be overridden by storage JSON.
     *
     * @return array{name:string,questions:array<int, array{title:string,description:string,choices:array<int,string>,answer:int}>}|null
     */
    public function topic(string $key): ?array
    {
        $topics = config('exams.topics', []);
        if (! array_key_exists($key, $topics)) {
            return null;
        }

        $base = $topics[$key];
        $name = (string) ($base['name'] ?? ucfirst($key));
        $questions = $this->loadOverride($key) ?? Arr::get($base, 'questions', []);

        return [
            'name' => $name,
            'questions' => is_array($questions) ? $questions : [],
        ];
    }

    /**
     * Persist full questions array override for a topic to storage.
     *
     * @param  array<int, array{title:string,description:string,choices:array<int,string>,answer:int}>  $questions
     */
    public function saveQuestions(string $key, array $questions): void
    {
        // Basic normalization: reindex numerically
        $questions = array_values($questions);
        $disk = Storage::disk('local');
        $path = $this->topicPath($key);
        $dir = dirname($path);
        if (! $disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }
        $disk->put($path, json_encode($questions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Add a question to the end of list and persist.
     *
     * @param  array{title:string,description:string,choices:array<int,string>,answer:int}  $question
     */
    public function addQuestion(string $key, array $question): void
    {
        $topic = $this->topic($key);
        if ($topic === null) {
            return;
        }
        $questions = $topic['questions'];
        $questions[] = $question;
        $this->saveQuestions($key, $questions);
    }

    /**
     * Update a question at index and persist.
     *
     * @param  array{title:string,description:string,choices:array<int,string>,answer:int}  $question
     */
    public function updateQuestion(string $key, int $index, array $question): void
    {
        $topic = $this->topic($key);
        if ($topic === null) {
            return;
        }
        $questions = $topic['questions'];
        if (! array_key_exists($index, $questions)) {
            return;
        }
        $questions[$index] = $question;
        $this->saveQuestions($key, $questions);
    }

    /**
     * Delete a question at index and persist.
     */
    public function deleteQuestion(string $key, int $index): void
    {
        $topic = $this->topic($key);
        if ($topic === null) {
            return;
        }
        $questions = $topic['questions'];
        if (! array_key_exists($index, $questions)) {
            return;
        }
        unset($questions[$index]);
        $this->saveQuestions($key, array_values($questions));
    }

    /**
     * Load override JSON from storage if exists.
     *
     * @return array<int, mixed>|null
     */
    protected function loadOverride(string $key): ?array
    {
        $disk = Storage::disk('local');
        $path = $this->topicPath($key);
        if (! $disk->exists($path)) {
            return null;
        }
        $content = $disk->get($path);
        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : null;
    }

    protected function topicPath(string $key): string
    {
        return 'exams/'.$key.'.json';
    }
}
