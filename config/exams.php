<?php

return [
    'topics' => [
        'php' => [
            'name' => 'PHP',
            'questions' => [
                [
                    'title' => 'PHP opening tag',
                    'description' => 'Which opening tag is recommended for PHP?',
                    'choices' => ['<?', '<?php', '<script>', '<!php'],
                    'answer' => 1,
                ],
                [
                    'title' => 'String concatenation',
                    'description' => 'Which operator concatenates strings in PHP?',
                    'choices' => ['+', '.', '&&', '::'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Array function count',
                    'description' => 'Which function returns the number of elements in an array?',
                    'choices' => ['size()', 'length()', 'count()', 'items()'],
                    'answer' => 2,
                ],
                [
                    'title' => 'Null coalescing',
                    'description' => 'What does the ?? operator do?',
                    'choices' => ['Ternary', 'Spaceship compare', 'Null coalescing', 'Bitwise or'],
                    'answer' => 2,
                ],
                [
                    'title' => 'Composer file',
                    'description' => 'Which file manages PHP dependencies?',
                    'choices' => ['package.json', 'composer.json', 'requirements.txt', 'Gemfile'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Strict types',
                    'description' => 'How to enable strict types in a file?',
                    'choices' => ['declare(strict_types=1);', 'use strict;', '#strict', 'pragma strict'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Variable variable',
                    'description' => 'Which syntax refers to a variable whose name is stored in another variable?',
                    'choices' => ['$var$', '$$var', '${var}', '$var->var'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Namespaces',
                    'description' => 'Which keyword defines a namespace?',
                    'choices' => ['package', 'module', 'namespace', 'use'],
                    'answer' => 2,
                ],
                [
                    'title' => 'Anonymous function',
                    'description' => 'What keyword creates an anonymous function?',
                    'choices' => ['func', 'lambda', 'closure', 'function'],
                    'answer' => 3,
                ],
                [
                    'title' => 'Types',
                    'description' => 'Which is a scalar type in PHP?',
                    'choices' => ['array', 'callable', 'int', 'object'],
                    'answer' => 2,
                ],
            ],
        ],
        'laravel' => [
            'name' => 'Laravel',
            'questions' => [
                [
                    'title' => 'Artisan migrate',
                    'description' => 'Which command runs database migrations?',
                    'choices' => ['php artisan seed', 'php artisan migrate', 'php artisan cache:clear', 'php artisan make:migration'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Eloquent primary key',
                    'description' => 'What is the default primary key column name in Eloquent?',
                    'choices' => ['uuid', 'key', 'id', 'pk'],
                    'answer' => 2,
                ],
                [
                    'title' => 'Configuration',
                    'description' => 'Where should you access env values in Laravel apps?',
                    'choices' => ['Anywhere with env()', 'Only in config files with env()', 'In controllers with env()', 'In routes with env()'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Validation',
                    'description' => 'What class is used for dedicated validation?',
                    'choices' => ['FormRequest', 'Validator', 'Rule', 'Middleware'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Routing file',
                    'description' => 'Where are HTTP routes defined?',
                    'choices' => ['routes/web.php', 'app/Http/Kernel.php', 'config/app.php', 'database/seeders'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Blade echo',
                    'description' => 'Which syntax safely echos data in Blade?',
                    'choices' => ['{!! $var !!}', '{{ $var }}', '<?php echo $var ?>', '${var}'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Queue interface',
                    'description' => 'Which interface marks a job to run on queue?',
                    'choices' => ['ShouldQueue', 'Queueable', 'Queued', 'AsyncJob'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Database ops',
                    'description' => 'What should you prefer over raw DB:: queries?',
                    'choices' => ['Plain SQL', 'Eloquent models and relationships', 'XML files', 'INI configs'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Routes helper',
                    'description' => 'How do you generate links to named routes?',
                    'choices' => ['url()', 'route()', 'asset()', 'link_to()'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Testing',
                    'description' => 'Which test style does this project use?',
                    'choices' => ['PHPUnit classes', 'Pest', 'Codeception', 'Behat'],
                    'answer' => 1,
                ],
            ],
        ],
        'nodejs' => [
            'name' => 'Node.js',
            'questions' => [
                [
                    'title' => 'Package manager',
                    'description' => 'Which file lists Node.js dependencies?',
                    'choices' => ['composer.json', 'package.json', 'requirements.txt', 'Gemfile'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Runtime',
                    'description' => 'Node.js is primarily used to run JavaScript on the ____ side.',
                    'choices' => ['client', 'server', 'database', 'browser only'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Module system',
                    'description' => 'Which is a Node.js module system?',
                    'choices' => ['CommonJS', 'AMD', 'Require', 'PSR-4'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Async pattern',
                    'description' => 'Which keyword waits for a Promise?',
                    'choices' => ['await', 'defer', 'sync', 'yield'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Package runner',
                    'description' => 'Which command runs a script from package.json?',
                    'choices' => ['npm run <script>', 'composer run <script>', 'node run <script>', 'yarn php <script>'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Version manager',
                    'description' => 'Which tool manages multiple Node versions?',
                    'choices' => ['nvm', 'pyenv', 'rbenv', 'asdf-php'],
                    'answer' => 0,
                ],
                [
                    'title' => 'HTTP module',
                    'description' => 'Which module can create an HTTP server?',
                    'choices' => ['http', 'dns', 'fs', 'process'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Package lock',
                    'description' => 'Which file locks exact dependency versions?',
                    'choices' => ['package-lock.json', 'yarn.lock', 'composer.lock', 'pnpm-lock.yaml'],
                    'answer' => 0,
                ],
                [
                    'title' => 'JS runtime',
                    'description' => 'Node.js is built on which engine?',
                    'choices' => ['SpiderMonkey', 'V8', 'Java', 'Zend'],
                    'answer' => 1,
                ],
                [
                    'title' => 'ESM import',
                    'description' => 'Which keyword imports a module in ESM?',
                    'choices' => ['import', 'require', 'include', 'use'],
                    'answer' => 0,
                ],
            ],
        ],
        'express' => [
            'name' => 'Express',
            'questions' => [
                [
                    'title' => 'Express framework',
                    'description' => 'Express is a web framework for which runtime?',
                    'choices' => ['PHP', 'Node.js', 'Python', 'Ruby'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Router method',
                    'description' => 'Which method handles GET requests?',
                    'choices' => ['app.get()', 'app.post()', 'app.fetch()', 'app.http()'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Middleware',
                    'description' => 'What is middleware used for?',
                    'choices' => ['DB queries only', 'Request/response processing', 'HTML templating', 'Binary parsing only'],
                    'answer' => 1,
                ],
                [
                    'title' => 'Body parsing',
                    'description' => 'Which middleware parses JSON bodies?',
                    'choices' => ['express.json()', 'express.body()', 'app.urlencoded()', 'body-parser.text()'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Static files',
                    'description' => 'Which middleware serves static files?',
                    'choices' => ['express.static()', 'express.assets()', 'app.static()', 'serve.static()'],
                    'answer' => 0,
                ],
                [
                    'title' => 'Route params',
                    'description' => 'What syntax defines a route parameter?',
                    'choices' => ['/users/:id', '/users/{id}', '/users/<id>', '/users[$id]'],
                    'answer' => 0,
                ],
                [
                    'title' => 'HTTP status',
                    'description' => 'Which status indicates resource not found?',
                    'choices' => ['200', '301', '404', '500'],
                    'answer' => 2,
                ],
                [
                    'title' => 'Template engines',
                    'description' => 'Which is a common Express view engine?',
                    'choices' => ['Blade', 'Twig', 'EJS', 'Razor'],
                    'answer' => 2,
                ],
                [
                    'title' => 'Listen',
                    'description' => 'Which method starts the server listening?',
                    'choices' => ['app.open()', 'app.listen()', 'app.start()', 'app.run()'],
                    'answer' => 1,
                ],
                [
                    'title' => 'JSON response',
                    'description' => 'How do you send JSON in Express?',
                    'choices' => ['res.html()', 'res.json()', 'res.body()', 'res.text()'],
                    'answer' => 1,
                ],
            ],
        ],
    ],
];
