<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:create {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a service, API service, and/or view service for a model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model') ?? $this->ask('Enter model name (e.g., User)');
        $namespace = $this->ask('Enter namespace (default: empty)', '');
        
        // Ask for type of service to generate
        $type = $this->choice(
            'Generate API service, View service, or both?',
            ['api', 'view', 'both'],
            2
        );

        // Ensure the Services directory exists
        $servicePath = app_path("Services/" . ($namespace ? "$namespace/" : ""));
        File::ensureDirectoryExists($servicePath);

        // Create the required files
        if ($type === 'api' || $type === 'both') {
            $this->generateFile($servicePath, "{$model}Service.php", 'ApiService.stub', $model, $namespace);
            $this->info("API Service created: {$servicePath}{$model}Service.php");
        }

        if ($type === 'view' || $type === 'both') {
            $this->generateFile($servicePath, "{$model}Service.php", 'Service.stub', $model, $namespace);
            $this->info("View Service created: {$servicePath}{$model}Service.php");
        }

        $this->info('Service creation completed.');
    }

    private function generateFile($path, $fileName, $stubName, $model, $namespace)
    {
        $stub = File::get(base_path("stubs/$stubName"));

        $content = str_replace(
            ['{{name}}', '{{model}}', '{{namespace}}'],
            [$model, $model, $namespace ? "\\$namespace" : ""],
            $stub
        );

        File::put($path . $fileName, $content);
    }
}
