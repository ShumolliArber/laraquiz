<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Topic;

uses(RefreshDatabase::class);

it('shows the exams index to guests', function () {
    $response = $this->get('/');
    $response->assertSuccessful();
    $response->assertSee('Choose an Exam');
});

it('shows an exam form for a valid topic', function () {
    // Seed a topic and a few questions
    $topic = Topic::query()->create(['key' => 'php', 'name' => 'PHP']);
    foreach (range(0, 9) as $i) {
        $topic->questions()->create([
            'title' => 'Q'.($i+1),
            'description' => 'D'.($i+1),
            'choice_0' => 'A',
            'choice_1' => 'B',
            'choice_2' => 'C',
            'choice_3' => 'D',
            'answer' => $i % 4,
            'position' => $i,
        ]);
    }
    $response = $this->get('/exams/php');
    $response->assertSuccessful();
    $response->assertSee('PHP Exam');
});

it('redirects to index for invalid topic', function () {
    // No seed for 'invalid' so it should redirect
    $response = $this->get('/exams/invalid');
    $response->assertRedirect('/');
});

it('grades a submission and shows results', function () {
    $topic = Topic::query()->create(['key' => 'php', 'name' => 'PHP']);
    $answers = [];
    foreach (range(0, 9) as $i) {
        $answer = $i % 4;
        $topic->questions()->create([
            'title' => 'Q'.($i+1),
            'description' => 'D'.($i+1),
            'choice_0' => 'A',
            'choice_1' => 'B',
            'choice_2' => 'C',
            'choice_3' => 'D',
            'answer' => $answer,
            'position' => $i,
        ]);
        $answers[$i] = $answer;
    }

    $response = $this->post('/exams/php', [
        'answers' => $answers,
    ]);

    $response->assertSuccessful();
    $response->assertSee('You scored 10/10');
    $response->assertSee('100%');
});

it('counts total submissions and by topic', function () {
    $topic = Topic::query()->create(['key' => 'php', 'name' => 'PHP']);
    $answers = [];
    foreach (range(0, 9) as $i) {
        $answer = $i % 4;
        $topic->questions()->create([
            'title' => 'Q'.($i+1),
            'description' => 'D'.($i+1),
            'choice_0' => 'A',
            'choice_1' => 'B',
            'choice_2' => 'C',
            'choice_3' => 'D',
            'answer' => $answer,
            'position' => $i,
        ]);
        $answers[$i] = $answer;
    }

    $this->post('/exams/php', ['answers' => $answers])->assertSuccessful();
    $this->post('/exams/php', ['answers' => $answers])->assertSuccessful();

    $resp = $this->get('/exams/submissions/count');
    $resp->assertSuccessful()->assertJson(['count' => 2]);

    $resp2 = $this->get('/exams/submissions/count?topic=php');
    $resp2->assertSuccessful()->assertJson(['count' => 2, 'topic' => 'php']);
});
