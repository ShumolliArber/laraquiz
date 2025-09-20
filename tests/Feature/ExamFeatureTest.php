<?php

it('shows the exams index to guests', function () {
    $response = $this->get('/');
    $response->assertSuccessful();
    $response->assertSee('Choose an Exam');
});

it('shows an exam form for a valid topic', function () {
    $response = $this->get('/exams/php');
    $response->assertSuccessful();
    $response->assertSee('PHP Exam');
});

it('redirects to index for invalid topic', function () {
    $response = $this->get('/exams/invalid');
    $response->assertRedirect('/');
});

it('grades a submission and shows results', function () {
    // Grab config to craft a perfect submission
    $exam = config('exams.topics.php');
    $answers = [];
    foreach (array_slice($exam['questions'], 0, 10) as $i => $q) {
        $answers[$i] = $q['answer'];
    }

    $response = $this->post('/exams/php', [
        'answers' => $answers,
    ]);

    $response->assertSuccessful();
    $response->assertSee('You scored 10/10');
    $response->assertSee('100%');
});
