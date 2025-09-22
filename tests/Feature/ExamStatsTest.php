<?php

use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns stats with topics and questions counts', function () {
    // Create topics
    $t1 = Topic::query()->create(['key' => 'alpha', 'name' => 'Alpha']);
    $t2 = Topic::query()->create(['key' => 'beta', 'name' => 'Beta']);

    // Add questions to alpha (2) and beta (1)
    $t1->questions()->create([
        'title' => 'Q1', 'description' => 'D1',
        'choice_0' => 'A', 'choice_1' => 'B', 'choice_2' => 'C', 'choice_3' => 'D',
        'answer' => 0, 'position' => 0,
    ]);
    $t1->questions()->create([
        'title' => 'Q2', 'description' => 'D2',
        'choice_0' => 'A', 'choice_1' => 'B', 'choice_2' => 'C', 'choice_3' => 'D',
        'answer' => 1, 'position' => 1,
    ]);
    $t2->questions()->create([
        'title' => 'QB1', 'description' => 'DB1',
        'choice_0' => 'A', 'choice_1' => 'B', 'choice_2' => 'C', 'choice_3' => 'D',
        'answer' => 2, 'position' => 0,
    ]);

    $response = $this->getJson('/exams/stats');

    $response->assertSuccessful();
    $response->assertJson(function ($json) {
        $json->where('topics_count', 2)
            ->has('topics', 2)
            ->etc();
    });

    $data = $response->json();
    $topics = collect($data['topics']);
    expect($topics->firstWhere('key', 'alpha')['questions_count'])->toBe(2);
    expect($topics->firstWhere('key', 'beta')['questions_count'])->toBe(1);
});
