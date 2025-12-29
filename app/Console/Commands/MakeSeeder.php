<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeSeeder extends Command
{

    protected $signature = 'app:make-seeder {name} {entity} {attributes*}';
    protected $description = 'this command to make seeder class with attributes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $entity = $this->argument('entity');
        $attributes = $this->argument('attributes');
        $seederPath = database_path("seeders/v1/{$entity}/{$name}/{$name}Seeder.php");
        Artisan::call('make:seeder', ['name' => "v1/{$entity}/{$name}/{$name}Seeder"]);
        File::put($seederPath, $this->putSeederContent($name,$entity,$attributes));
        $this->info('Seeder created successfully.');
    }

    public function putSeederContent($name,$entity,$attributes)
    {
        $attributeAssignments = '';
        foreach ($attributes as $attribute) {
            [$type, $field] = explode(':', $attribute);
            if ($type == "string")
                $attributeAssignments .= "'$field' => 'name',\n            ";
            else
                $attributeAssignments .= "'$field' => '1',\n            ";
        }
        return "<?php

namespace Database\Seeders\\v1\\{$entity}\\{$name};

use App\Models\\{$name};
use Illuminate\Database\Seeder;

class {$name}Seeder extends Seeder
{
    public function run(): void
    {
        $name::create([
             $attributeAssignments
        ]);
    }
}
";
    }
}
