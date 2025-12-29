<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'app:make-service {name} {entity} {attributes*}';
    protected $description = 'this command to make service class with attributes';


    public function handle()
    {

        $name = $this->argument('name');
        $entity = $this->argument('entity');
        $attributes = $this->argument('attributes');

        $this->createService($name,$entity,$attributes);
    }

    protected function createService($name,$entity,$attributes)
    {
        // Create Service
        $servicePath = app_path("Services/v1/{$entity}/{$name}/{$name}Service.php");
        if (!File::exists(dirname($servicePath))) {
            File::makeDirectory(dirname($servicePath), 0755, true);
        }
        File::put($servicePath, $this->getServiceContent($name,$entity,$attributes));
        $this->info('Service created successfully.');
    }
    protected function getServiceContent($name, $entity, $attributes)
    {
        $lower = lcfirst($name);
        $attributeAssignments = '';
        foreach ($attributes as $attribute) {
            [$type,$field] = explode(':', $attribute);
            $attributeAssignments .= "'$field' => \$request->$field,\n            ";
        }
        $attributeAssignmentsUpdate = '';
        foreach ($attributes as $attribute) {
            [$type,$field] = explode(':', $attribute);
            $attributeAssignmentsUpdate .= "\${$lower}->$field = \$request->$field ?? \${$lower}->$field;\n        ";
        }

        return "<?php

namespace App\Services\\v1\\{$entity}\\{$name};

use App\Repositories\\v1\\Interface\\{$entity}\\{$name}\\I{$name};

class {$name}Service
{
    protected I{$name} \${$lower};

    public function __construct(I{$name} \${$lower})
    {
        \$this->{$lower} = \${$lower};
    }

    public function index(\$request,\$limit)
    {
        \$query = \$request->input('query');
        return \$this->{$lower}->get(\$query,\$limit);
    }

    public function store(\$request)
    {
        \${$lower} = [
            {$attributeAssignments}
        ];
        return \$this->{$lower}->store(\${$lower});
    }

    public function show(\${$lower})
    {
        return \$this->{$lower}->show(\${$lower});
    }

    public function update(\$request, \${$lower})
    {
        {$attributeAssignmentsUpdate}
        return \$this->{$lower}->update(\${$lower});
    }

    public function delete(\${$lower})
    {
        return \$this->{$lower}->delete(\${$lower});
    }
}";
    }
}
