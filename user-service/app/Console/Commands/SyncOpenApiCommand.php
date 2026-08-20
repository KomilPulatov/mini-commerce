<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class SyncOpenApiCommand extends Command
{
    protected $signature = 'openapi:sync';
    protected $description = 'Syncs docs/openapi.yaml to storage/api-docs JSON and YAML for Swagger UI';

    public function handle(): int
    {
        $sourcePath = base_path('docs/openapi.yaml');

        if (! file_exists($sourcePath)) {
            $this->error("OpenAPI specification file not found at: {$sourcePath}");
            return Command::FAILURE;
        }

        $targetDir = storage_path('api-docs');
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $yamlContent = file_get_contents($sourcePath);
        file_put_contents($targetDir . '/api-docs.yaml', $yamlContent);

        $parsed = Yaml::parse($yamlContent);
        file_put_contents(
            $targetDir . '/api-docs.json',
            json_encode($parsed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        $this->info('OpenAPI specification synchronized successfully from docs/openapi.yaml.');

        return Command::SUCCESS;
    }
}