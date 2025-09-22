<?php

use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('sets a visitor_id cookie on first submission and persists the record', function () {
    $topic = Topic::query()->create(['key' => 'php', 'name' => 'PHP']);
    foreach (range(0, 9) as $i) {
        $topic->questions()->create([
            'title' => 'Q'.($i + 1), 'description' => 'D'.($i + 1),
            'choice_0' => 'A', 'choice_1' => 'B', 'choice_2' => 'C', 'choice_3' => 'D',
            'answer' => $i % 4, 'position' => $i,
        ]);
    }

    $answers = [];
    foreach (range(0, 9) as $i) {
        $answers[$i] = $i % 4;
    }

    $response = $this->post('/exams/php', ['answers' => $answers]);

    $response->assertSuccessful();
    $response->assertCookie('visitor_id');
});

it('only returns submissions for the same visitor', function () {
    $topic = Topic::query()->create(['key' => 'php', 'name' => 'PHP']);
    foreach (range(0, 9) as $i) {
        $topic->questions()->create([
            'title' => 'Q'.($i + 1), 'description' => 'D'.($i + 1),
            'choice_0' => 'A', 'choice_1' => 'B', 'choice_2' => 'C', 'choice_3' => 'D',
            'answer' => $i % 4, 'position' => $i,
        ]);
    }
    $answers = [];
    foreach (range(0, 9) as $i) {
        $answers[$i] = $i % 4;
    }

    // Visitor A submits and gets cookie
    $respA = $this->post('/exams/php', ['answers' => $answers]);
    $cookie = $respA->getCookie('visitor_id');
    expect($cookie)->not->toBeNull();

    // Visitor B (no cookie) submits as different session
    $this->flushSession();
    $respB = $this->post('/exams/php', ['answers' => $answers]);
    $cookieB = $respB->getCookie('visitor_id');
    expect($cookieB)->not->toBeNull();
    expect($cookieB->getValue())->not->toBe($cookie->getValue());

    // Query A's submissions
    $jsonA = $this->withCookie('visitor_id', $cookie->getValue())->get('/exams/my-submissions');
    $jsonA->assertSuccessful();
    $dataA = $jsonA->json();
    expect($dataA['submissions'])->toBeArray()->toHaveCount(1);

    // Query B's submissions
    $jsonB = $this->withCookie('visitor_id', $cookieB->getValue())->get('/exams/my-submissions');
    $jsonB->assertSuccessful();
    $dataB = $jsonB->json();
    expect($dataB['submissions'])->toBeArray()->toHaveCount(1);
});
