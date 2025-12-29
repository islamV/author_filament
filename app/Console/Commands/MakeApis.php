<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeApis extends Command
{

    protected $signature = 'app:make-api {name} {entity} {attributes*}';
    protected $description = 'this command to make api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $entity = $this->argument('entity');

        $lower = strtolower($name);
        $lowerEntity = strtolower($entity);
        $snakeCase = preg_replace('/([a-z])([A-Z])/', '$1-$2', $name);
        $snakeCaseLower = strtolower($snakeCase);
        $controllerName = lcfirst(ucwords($name));
        $apiPath = base_path('routes/api/v1/' . $lowerEntity . '/' . $lower . '.php');
        $pluralName = Str::plural(strtolower($name));
        if (!File::exists(dirname($apiPath))) {
            File::makeDirectory(dirname($apiPath), 0755, true);
        }
        File::put($apiPath, $this->getRoutesTemplate($name,$entity, $pluralName,$lower,$snakeCaseLower));
    }

    protected function getRoutesTemplate($name, $entity, $pluralName,$lower,$snakeCaseLower)
    {
        $entity = strtolower($entity);
        return "<?php


use App\Http\Controllers\\v1\\{$entity}\\{$name}\\{$name}Controller;
use Illuminate\Support\Facades\Route;


Route::middleware(['cors','lang'])->prefix('v1/{$entity}')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('{$pluralName}', [{$name}Controller::class, 'index']);
        Route::get('{$pluralName}/{{$snakeCaseLower}}', [{$name}Controller::class, 'show']);
        Route::get('search-{$pluralName}', [{$name}Controller::class, 'search']);
        Route::post('{$pluralName}', [{$name}Controller::class, 'store']);
        Route::patch('{$pluralName}/{{$snakeCaseLower}}', [{$name}Controller::class, 'update']);
        Route::delete('{$pluralName}/{{$snakeCaseLower}}', [{$name}Controller::class, 'destroy']);
    });
});
";
    }
}
