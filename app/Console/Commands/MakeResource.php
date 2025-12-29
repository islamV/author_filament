<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeResource extends Command
{

    protected $signature = 'app:make-resource {name} {entity} {attributes*}';
    protected $description = 'this command to make resource class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $entity = $this->argument('entity');
        $attributes = $this->argument('attributes');
        $modelPath = app_path("Http/Resources/v1/{$entity}/{$name}/{$name}Resource.php");
        Artisan::call('make:resource', ['name' => "v1/{$entity}/{$name}/{$name}Resource"]);


        if (!File::exists(dirname($modelPath))) {
            File::makeDirectory(dirname($modelPath), 0755, true);
        }
        File::put($modelPath, $this->putResourceContent($name,$entity, $attributes));
        $this->info('Resource created successfully.');
    }
    public function putResourceContent($name,$entity,$attributes): string
    {
        $attributeAssignments = '';
        foreach ($attributes as $attribute) {
            [$type, $field] = explode(':', $attribute);
            $attributeAssignments .= "'$field' => \$this->$field,\n            ";


        }
        return "<?php

namespace App\Http\Resources\\v1\\{$entity}\\{$name};

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {$name}Resource extends JsonResource
{
    public function toArray(Request \$request): array
    {
        return [
            'id' => \$this->id,
            {$attributeAssignments}
        ];
    }
}
";
    }
}
