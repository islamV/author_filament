<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeFullStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-full-structure {name} {entity} {attributes*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command to create all services for all of your entity and adjust its structure to be like everything in its directory';

    public function handle()
    {
        $name = $this->argument('name');
        $entity = $this->argument('entity');
        $attributes = $this->argument('attributes');
        Artisan::call('app:make-api', ['name' => $name, "entity" => $entity, "attributes" => $attributes]);

        Artisan::call('app:make-model', ['name' => $name, "attributes" => $attributes]);
        $this->info('Model created successfully.');
        $this->info('Migration created successfully.');

        Artisan::call('app:make-repository', ['name' => $name, "entity" => $entity]);
        $this->info('Repository and Interface created successfully.');

        Artisan::call('app:make-service', ['name' => $name, "entity" => $entity, "attributes" => $attributes]);
        $this->info('Services created successfully.');

        Artisan::call('app:make-controller', ['name' => $name, "entity" => $entity]);
        $this->info('Controller created successfully.');

        Artisan::call('app:make-request', ['name' => $name, "entity" => $entity, "attributes" => $attributes]);
        $this->info('Requests created successfully.');

        Artisan::call('app:make-resource', ['name' => $name, "entity" => $entity, "attributes" => $attributes]);
        $this->info('Resource created successfully.');

        Artisan::call('app:make-seeder', ['name' => $name, "entity" => $entity, "attributes" => $attributes]);
        $this->info('Seeder created successfully.');

        Artisan::call('app:make-api', ['name' => $name, "entity" => $entity, "attributes" => $attributes]);
        $this->info("Api file created for $name.");

        Artisan::call('make:factory', ['name' => "v1/{$entity}/{$name}/{$name}Factory"]);
        $this->info('Factory created successfully.');

        $this->info('All components created successfully.');
    }
}
