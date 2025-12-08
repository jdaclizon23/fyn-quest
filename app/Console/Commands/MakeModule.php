<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name : The name of the module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module with Clean Architecture structure';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $moduleName = Str::studly($this->argument('name'));
        $modulePath = app_path("Modules/{$moduleName}");
        $moduleNamespace = "App\\Modules\\{$moduleName}";

        // Check if module already exists
        if (File::exists($modulePath)) {
            $this->error("Module '{$moduleName}' already exists!");
            return Command::FAILURE;
        }

        $this->info("Creating module: {$moduleName}");

        // Create directory structure
        $this->createDirectoryStructure($modulePath);

        // Create files
        $this->createServiceProvider($moduleName, $moduleNamespace, $modulePath);
        $this->createModel($moduleName, $moduleNamespace, $modulePath);
        $this->createRepositoryInterface($moduleName, $moduleNamespace, $modulePath);
        $this->createRepositoryImplementation($moduleName, $moduleNamespace, $modulePath);
        $this->createRoutesFile($moduleName, $modulePath);
        $this->createFlowDocumentation($moduleName, $modulePath);
        $this->createReadme($moduleName, $modulePath);

        // Register service provider
        $this->registerServiceProvider($moduleNamespace);

        $this->info("✅ Module '{$moduleName}' created successfully!");
        $this->info("📁 Location: {$modulePath}");
        $this->newLine();
        $this->info("Next steps:");
        $this->line("1. Update the model in: app/Modules/{$moduleName}/Domain/Models/");
        $this->line("2. Update repository interface in: app/Modules/{$moduleName}/Domain/Repositories/");
        $this->line("3. Create your actions in: app/Modules/{$moduleName}/Application/Actions/");
        $this->line("4. Create your controllers in: app/Modules/{$moduleName}/Presentation/Http/Controllers/");

        return Command::SUCCESS;
    }

    /**
     * Create directory structure
     */
    protected function createDirectoryStructure(string $modulePath): void
    {
        $directories = [
            "{$modulePath}/Domain/Models",
            "{$modulePath}/Domain/Repositories",
            "{$modulePath}/Domain/Services",
            "{$modulePath}/Application/Actions",
            "{$modulePath}/Application/DTO",
            "{$modulePath}/Infrastructure/Repositories",
            "{$modulePath}/Infrastructure/Services",
            "{$modulePath}/Infrastructure/Providers",
            "{$modulePath}/Presentation/Http/Controllers",
            "{$modulePath}/Presentation/Http/Requests",
            "{$modulePath}/Presentation/Http/Resources",
            "{$modulePath}/Presentation/Routes",
        ];

        foreach ($directories as $directory) {
            File::makeDirectory($directory, 0755, true);
            $this->line("Created directory: {$directory}");
        }
    }

    /**
     * Create service provider
     */
    protected function createServiceProvider(string $moduleName, string $namespace, string $modulePath): void
    {
        $modelName = Str::studly(Str::singular($moduleName));
        $serviceProviderName = Str::studly($moduleName) . "ServiceProvider";
        $serviceProviderPath = "{$modulePath}/Infrastructure/Providers/{$serviceProviderName}.php";
        $routeFileName = Str::snake($moduleName) . '.php';

        $stub = $this->getStub('service-provider');
        $stub = str_replace('{{ModuleName}}', $moduleName, $stub);
        $stub = str_replace('{{Namespace}}', $namespace, $stub);
        $stub = str_replace('{{ServiceProviderName}}', $serviceProviderName, $stub);
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{RouteFileName}}', $routeFileName, $stub);

        File::put($serviceProviderPath, $stub);
        $this->line("Created: {$serviceProviderName}.php");
    }

    /**
     * Create model
     */
    protected function createModel(string $moduleName, string $namespace, string $modulePath): void
    {
        $modelName = Str::studly(Str::singular($moduleName));
        $modelPath = "{$modulePath}/Domain/Models/{$modelName}.php";
        $tableName = Str::snake(Str::plural($modelName));

        $stub = $this->getStub('model');
        $stub = str_replace('{{ModuleName}}', $moduleName, $stub);
        $stub = str_replace('{{Namespace}}', $namespace, $stub);
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{TableName}}', $tableName, $stub);

        File::put($modelPath, $stub);
        $this->line("Created: {$modelName}.php");
    }

    /**
     * Create repository interface
     */
    protected function createRepositoryInterface(string $moduleName, string $namespace, string $modulePath): void
    {
        $modelName = Str::studly(Str::singular($moduleName));
        $repositoryInterfaceName = "{$modelName}RepositoryInterface";
        $repositoryPath = "{$modulePath}/Domain/Repositories/{$repositoryInterfaceName}.php";

        $stub = $this->getStub('repository-interface');
        $stub = str_replace('{{ModuleName}}', $moduleName, $stub);
        $stub = str_replace('{{Namespace}}', $namespace, $stub);
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{RepositoryInterfaceName}}', $repositoryInterfaceName, $stub);

        File::put($repositoryPath, $stub);
        $this->line("Created: {$repositoryInterfaceName}.php");
    }

    /**
     * Create repository implementation
     */
    protected function createRepositoryImplementation(string $moduleName, string $namespace, string $modulePath): void
    {
        $modelName = Str::studly(Str::singular($moduleName));
        $repositoryInterfaceName = "{$modelName}RepositoryInterface";
        $repositoryImplementationName = "{$modelName}Repository";
        $repositoryPath = "{$modulePath}/Infrastructure/Repositories/{$repositoryImplementationName}.php";

        $stub = $this->getStub('repository-implementation');
        $stub = str_replace('{{ModuleName}}', $moduleName, $stub);
        $stub = str_replace('{{Namespace}}', $namespace, $stub);
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{RepositoryInterfaceName}}', $repositoryInterfaceName, $stub);
        $stub = str_replace('{{RepositoryImplementationName}}', $repositoryImplementationName, $stub);

        File::put($repositoryPath, $stub);
        $this->line("Created: {$repositoryImplementationName}.php");
    }

    /**
     * Create routes file
     */
    protected function createRoutesFile(string $moduleName, string $modulePath): void
    {
        $routeFileName = Str::snake($moduleName) . '.php';
        $routePath = "{$modulePath}/Presentation/Routes/{$routeFileName}";

        $stub = $this->getStub('routes');
        $stub = str_replace('{{ModuleName}}', $moduleName, $stub);
        $stub = str_replace('{{RouteFileName}}', $routeFileName, $stub);

        File::put($routePath, $stub);
        $this->line("Created: {$routeFileName}");
    }

    /**
     * Create flow documentation
     */
    protected function createFlowDocumentation(string $moduleName, string $modulePath): void
    {
        $flowPath = "{$modulePath}/Flow.md";

        $stub = $this->getStub('flow');
        $stub = str_replace('{{ModuleName}}', $moduleName, $stub);

        File::put($flowPath, $stub);
        $this->line("Created: Flow.md");
    }

    /**
     * Create README
     */
    protected function createReadme(string $moduleName, string $modulePath): void
    {
        $readmePath = "{$modulePath}/README.md";
        $modelName = Str::studly(Str::singular($moduleName));

        $stub = $this->getStub('readme');
        $stub = str_replace('{{ModuleName}}', $moduleName, $stub);
        $stub = str_replace('{{ModuleNameLower}}', Str::lower($moduleName), $stub);
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($readmePath, $stub);
        $this->line("Created: README.md");
    }

    /**
     * Register service provider in bootstrap/providers.php
     */
    protected function registerServiceProvider(string $namespace): void
    {
        $providersPath = base_path('bootstrap/providers.php');
        $moduleName = basename(str_replace('\\', '/', $namespace));
        $serviceProviderClass = "{$namespace}\\Infrastructure\\Providers\\" . Str::studly($moduleName) . "ServiceProvider";

        if (!File::exists($providersPath)) {
            $this->error("bootstrap/providers.php not found!");
            return;
        }

        $content = File::get($providersPath);

        // Check if already registered
        if (str_contains($content, $serviceProviderClass)) {
            $this->warn("Service provider already registered in bootstrap/providers.php");
            return;
        }

        // Add service provider before the closing bracket
        $content = preg_replace(
            '/\];\s*$/',
            "    {$serviceProviderClass}::class,\n];",
            $content
        );

        File::put($providersPath, $content);
        $this->info("✅ Service provider registered in bootstrap/providers.php");
    }

    /**
     * Get stub content
     */
    protected function getStub(string $type): string
    {
        $stubPath = __DIR__ . '/stubs/module-' . str_replace('_', '-', $type) . '.stub';
        
        if (!file_exists($stubPath)) {
            $this->error("Stub file not found: {$stubPath}");
            return '';
        }

        return file_get_contents($stubPath);
    }
}
