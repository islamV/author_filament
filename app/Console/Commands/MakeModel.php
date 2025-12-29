<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModel extends Command
{

    protected $signature = 'app:make-model {name} {attributes*}';
    protected $description = 'this command to make model class with attributes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $attributes = $this->argument('attributes');
        $modelPath = app_path("Models/{$name}.php");
        Artisan::call('make:model', ['name' => "{$name}","--migration" => true]);

        File::put($modelPath, $this->putModelContent($name,$attributes));
        $this->modifyMigration($name, $attributes);
        $this->info('Model created successfully.');
    }
    protected function modifyMigration($name, $attributes): void
    {

        $lowerName = lcfirst($name);
        $tableName = Str::snake(Str::plural($name));
        $migrationFileName = database_path('migrations') . '/' . now()->format('Y_m_d_His') . "_create_{$tableName}_table.php";
        $content = File::get($migrationFileName);

        $schemaFields = '';
        foreach ($attributes as $attribute) {
            [$type, $field] = explode(':', $attribute);
            if ($type == "foreignId")
                $schemaFields .= "\$table->$type('$field')->constrained()->cascadeOnDelete()->cascadeOnUpdate();\n            ";
            else
                $schemaFields .= "\$table->$type('$field');\n            ";
        }

        $content = str_replace(
            "\$table->id();",
            "\$table->id();\n            $schemaFields",
            $content
        );

        File::put($migrationFileName, $content);
        $this->info('Migration modified to include attributes.');
    }

    public function putModelContent($name,$attributes): string
    {
        $attributeAssignments = '';
        foreach ($attributes as $attribute) {
            [$type, $field] = explode(':', $attribute);
            $attributeAssignments .= "'$field',\n        ";


        }
        return "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    use HasFactory;
    protected \$fillable = [
        $attributeAssignments
    ];
    protected \$casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
}

";
    }
}
