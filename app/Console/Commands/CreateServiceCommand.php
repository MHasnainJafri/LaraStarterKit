<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CreateServiceCommand extends Command
{
    protected $signature = 'service:create {model?}';
    protected $description = 'Generate a Service, Controller, Request, Admin CRUD views, and optionally an API Repository';

    public function handle()
    {
        // 1️⃣ Get Model Name
        $model = $this->argument('model') ?? $this->ask('Enter model name (e.g., User)');
        $modelVariable = Str::camel($model); // Convert "User" -> "user"
        $namespace = trim($this->ask('Enter namespace (default: empty)', ''), '\\');

        // 2️⃣ Ask for Type
        $type = $this->choice('Generate API, View, or Both?', ['api', 'view', 'both'], 2);

        // 3️⃣ Generate View Service
        if ($type === 'view' || $type === 'both') {
            $this->generateFile(app_path("Services" . ($namespace ? "/$namespace" : '')), "{$model}Service.php", 'Service.stub', $model, $modelVariable, $namespace);
            $this->info("View Service created: app/Services/{$namespace}/{$model}Service.php");

            // Generate Controller
            $this->generateFile(app_path("Http/Controllers" . ($namespace ? "/$namespace" : '')), "{$model}Controller.php", 'Controller.stub', $model, $modelVariable, $namespace);
            $this->info("Controller created: app/Http/Controllers/{$namespace}/{$model}Controller.php");

            // Generate Request
            $this->generateFile(app_path("Http/Requests" . ($namespace ? "/$namespace" : '')), "{$model}Request.php", 'Request.stub', $model, $modelVariable, $namespace);
            $this->info("Request created: app/Http/Requests/{$namespace}/{$model}Request.php");

            // Generate Admin CRUD Views
            $viewPath = resource_path("views/admin/" . strtolower($model));
            File::ensureDirectoryExists($viewPath);

            // $this->generateFile($viewPath, "index.blade.php", 'view_index.stub', $model, $modelVariable, $namespace);
            // $this->generateFile($viewPath, "create.blade.php", 'view_create.stub', $model, $modelVariable, $namespace);
            // $this->generateFile($viewPath, "edit.blade.php", 'view_edit.stub', $model, $modelVariable, $namespace);
            // $this->info("Admin Views created in: resources/views/admin/" . strtolower($model));
        }

        // 4️⃣ Generate API Repository if API or Both is selected
        if ($type === 'api' || $type === 'both') {
            $this->info("Generating API Repository for $model...");
            Artisan::call("make:repository $model", 
            [
                'name' => $model,
                // '--resource' => "{$model}Resource",
                // '--searchable' => 'title,content',
                // '--sortable' => 'title,created_at',
                // '--relations' => 'author,comments',
                // '--cache-tag' => strtolower($model) . 's',
                // '--per-page' => '20',
            ]);

            $this->info("API Repository created for $model.");
        }

        $this->info('Service creation completed.');
    }

    private function generateFile($path, $fileName, $stubName, $model, $modelVariable, $namespace)
    {
        File::ensureDirectoryExists($path);

        $filePath = $path . DIRECTORY_SEPARATOR . $fileName;

        // Check if file already exists
        if (File::exists($filePath)) {
            if (!$this->confirm("The file '$fileName' already exists. Do you want to replace it?", false)) {
                $this->info("Skipping creation of '$fileName'.");
                return;
            }
        }

        // Generate file content
        $stub = File::get(base_path("stubs/$stubName"));
        $content = str_replace(
            ['{{name}}', '{{model}}', '{{modelVariable}}', '{{namespace}}'],
            [$model, $model, $modelVariable, $namespace ? "\\$namespace" : ""],
            $stub
        );

        File::put($filePath, $content);
        $this->info("Created file: $filePath");
    }
}