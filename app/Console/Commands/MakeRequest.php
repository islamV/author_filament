<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeRequest extends Command
{

    protected $signature = 'app:make-request {name} {entity} {attributes*}';
    protected $description = 'this command to make request classes with attributes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $entity = $this->argument('entity');
        $attributes = $this->argument('attributes');
        $storeRequestPath = app_path("Http/Requests/v1/{$entity}/{$name}/Store{$name}Request.php");
        $updateRequestPath = app_path("Http/Requests/v1/{$entity}/{$name}/Update{$name}Request.php");
        Artisan::call('make:request', ['name' => "v1/{$entity}/{$name}/Store{$name}Request"]);
        Artisan::call('make:request', ['name' => "v1/{$entity}/{$name}/Update{$name}Request"]);
        File::put($storeRequestPath, $this->putRequestContent($name,$entity,$attributes,"Store"));
        File::put($updateRequestPath, $this->putRequestContent($name,$entity,$attributes,"Update"));
        $this->info('Requests created successfully.');
    }

    public function putRequestContent($name,$entity,$attributes,$status): string
    {
        $attributeAssignments = '';
        foreach ($attributes as $attribute) {
            [$type, $field] = explode(':', $attribute);
            if ($status == "Store"){
                if ($type == "string" || $type == "text")
                    $attributeAssignments .= "'$field' => 'required|string',\n            ";
                else
                    $attributeAssignments .= "'$field' => 'required|numeric',\n            ";
            }
            else
            {
                if ($type == "string" || $type == "text")
                    $attributeAssignments .= "'$field' => 'string',\n            ";
                else
                    $attributeAssignments .= "'$field' => 'numeric',\n            ";
            }
        }
        return "<?php

namespace App\Http\Requests\\v1\\{$entity}\\{$name};

use Illuminate\Foundation\Http\FormRequest;

class {$status}{$name}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            {$attributeAssignments}
        ];
    }
}
";
    }
}
