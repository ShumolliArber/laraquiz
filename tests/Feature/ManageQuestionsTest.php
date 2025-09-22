<?php

use Illuminate\Support\Facades\Storage;

it('can show manage page for a topic', function () {
    $response = $this->get('/exams/php/manage');
    $response->assertSuccessful();
    $response->assertSee('Manage PHP Questions');
});

it('can add a new question to a topic', function () {
    Storage::fake('local');

    $payload = [
        'title' => 'New Q',
        'description' => 'Desc',
        'choices' => ['A', 'B', 'C', 'D'],
        'answer' => 2,
    ];

    $response = $this->post('/exams/php/questions', $payload);
    $response->assertRedirect('/exams/php/manage');

    $path = 'exams/php.json';
    Storage::disk('local')->assertExists($path);
    $data = json_decode(Storage::disk('local')->get($path), true);

    expect($data)->toBeArray();
    $found = collect($data)->contains(fn ($q) => $q['title'] === 'New Q');
    expect($found)->toBeTrue();
});

it('can edit and delete a question', function () {
    Storage::fake('local');

    // Seed one question
    Storage::disk('local')->put('exams/php.json', json_encode([
        [
            'title' => 'Q1',
            'description' => 'D1',
            'choices' => ['A', 'B', 'C', 'D'],
            'answer' => 1,
        ],
    ]));

    // Update question 0
    $update = [
        'title' => 'Q1 updated',
        'description' => 'D1 updated',
        'choices' => ['A', 'B', 'C', 'D'],
        'answer' => 3,
    ];

    $resp = $this->put('/exams/php/questions/0', $update);
    $resp->assertRedirect('/exams/php/manage');

    $data = json_decode(Storage::disk('local')->get('exams/php.json'), true);
    expect($data[0]['title'])->toBe('Q1 updated');
    expect($data[0]['answer'])->toBe(3);

    // Delete question 0
    $resp2 = $this->delete('/exams/php/questions/0');
    $resp2->assertRedirect('/exams/php/manage');

    $data2 = json_decode(Storage::disk('local')->get('exams/php.json'), true);
    expect($data2)->toHaveCount(0);
});
