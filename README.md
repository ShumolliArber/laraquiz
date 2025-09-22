# Laravel Quiz App

A simple, clean quiz application where you can:
- Create topics (from the home page)
- Add, edit, and delete questions for each topic
- Take an exam for a topic and see detailed results
- Inspect basic statistics via a JSON endpoint

This project has been developed 100% by AI, with Junie Coding Agent.

## Tech Stack
- PHP 8.3
- Laravel 12
- Pest 4 for testing
- Vite + TailwindCSS for the frontend assets (optional; the views work without building too)

## Getting Started

1. Clone the repository
2. Install dependencies
   - Composer: `composer install`
   - NPM (optional for assets): `npm install`
3. Environment
   - Copy `.env.example` to `.env`
   - Configure your database connection
4. Migrate the database
   - `php artisan migrate`
5. Run the application
   - `php artisan serve`
6. (Optional) Build or watch frontend assets
   - Dev: `npm run dev`
   - Build: `npm run build`

## Usage

- Home page: lists all topics and provides a form to create a new one.
- Create Topic: fill in a unique key (slug) and a display name.
- Manage Questions: from a topic page, click “Manage questions” to add, edit, or delete questions.
- Take Exam: select a topic to answer up to 10 questions and see your score/result page.
- Statistics: `GET /exams/stats` returns JSON with the total number of topics and per-topic questions counts.
- Submissions Count: `GET /exams/submissions/count?topic={key}` returns the count of submissions, optionally filtered by topic.

## Testing

- Run the entire test suite: `php artisan test`
- Run a specific group/file:
  - `php artisan test --filter=ExamFeatureTest`
  - `php artisan test --filter=ManageQuestions`
  - `php artisan test --filter=ExamStats`

## Notes
- Topics and questions are fully stored in the database (no config or JSON persistence required).
- If you don’t see frontend changes, run `npm run dev` or `npm run build`.

## License
This project is open-sourced software licensed under the MIT license.
