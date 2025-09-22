<?php

use App\Models\Question;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can show manage page for a topic', function () {
    $response = $this->get('/exams/php/manage');
    $response->assertSuccessful();
    $response->assertSee('Manage PHP Questions');
});

it('can add a new question to a topic', function () {
    $payload = [
        'title' => 'New Q',
        'description' => 'Desc',
        'choices' => ['A', 'B', 'C', 'D'],
        'answer' => 2,
    ];

    $response = $this->post('/exams/php/questions', $payload);
    $response->assertRedirect('/exams/php/manage');

    $topic = Topic::query()->where('key', 'php')->first();
    expect($topic)->not->toBeNull();
    $q = $topic->questions()->where('title', 'New Q')->first();
    expect($q)->not->toBeNull();
    expect($q->answer)->toBe(2);
});

it('can edit and delete a question', function () {
    // Ensure topic exists, bootstrap from config will handle if not

    // Create one question for php topic explicitly to ensure index 0 exists
    $topic = Topic::query()->firstOrCreate(['key' => 'php'], ['name' => 'PHP']);
    $topic->questions()->create([
        'title' => 'Q1',
        'description' => 'D1',
        'choice_0' => 'A',
        'choice_1' => 'B',
        'choice_2' => 'C',
        'choice_3' => 'D',
        'answer' => 1,
        'position' => 0,
    ]);

    // Update question 0
    $update = [
        'title' => 'Q1 updated',
        'description' => 'D1 updated',
        'choices' => ['A', 'B', 'C', 'D'],
        'answer' => 3,
    ];

    $resp = $this->put('/exams/php/questions/0', $update);
    $resp->assertRedirect('/exams/php/manage');

    $topic->refresh();
    $first = $topic->questions()->orderBy('position')->orderBy('id')->first();
    expect($first->title)->toBe('Q1 updated');
    expect($first->answer)->toBe(3);

    // Delete question 0
    $resp2 = $this->delete('/exams/php/questions/0');
    $resp2->assertRedirect('/exams/php/manage');

    $count = $topic->questions()->count();
    expect($count)->toBe(0);
});
