<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{

    protected $signature = 'app:make-repository {name} {entity}';
    protected $description = 'this command to make repository and interface classes with attributes';
    public function handle()
    {
        $name = $this->argument('name');
        $entity = $this->argument('entity');
        $this->createRepository($name,$entity);
    }
    protected function createRepository($name,$entity)
    {
        // Create Repository
        $repositoryPath = app_path("Repositories/v1/Implementation/{$entity}/{$name}/{$name}Repository.php");
        if (!File::exists(dirname($repositoryPath))) {
            File::makeDirectory(dirname($repositoryPath), 0755, true);
        }
        File::put($repositoryPath, $this->getRepositoryContent($name,$entity));
        $this->info('Repository created successfully.');

        // Create Interface
        $interfacePath = app_path("Repositories/v1/Interface/{$entity}/{$name}/I{$name}.php");
        if (!File::exists(dirname($interfacePath))) {
            File::makeDirectory(dirname($interfacePath), 0755, true);
        }
        File::put($interfacePath, $this->getInterfaceContent($name,$entity));
        $this->info('Repository Interface created successfully.');
    }
    protected function getRepositoryContent($name,$entity)
    {
        return "<?php

namespace App\Repositories\\v1\Implementation\\{$entity}\\{$name};

use App\Repositories\\v1\Interface\\{$entity}\\{$name}\\I{$name};
use App\Models\\{$name};

class {$name}Repository implements I{$name}
{

    public function get(\$request,\$limit = 10)
    {
        return {$name}::whereAny(['name'], 'LIKE', \"%{\$request}%\")->paginate(\$limit);
    }

    public function show(\$model)
    {
        return {$name}::findOrFail(\$model->id);
    }

    public function store(\$model)
    {
        return {$name}::create(\$model);
    }

    public function update(\$model)
    {
        return \$model->save();
    }

    public function delete(\$model)
    {
        return \$model->delete();
    }

}";
    }

    protected function getInterfaceContent($name,$entity)
    {
        return "<?php

namespace App\Repositories\\v1\Interface\\{$entity}\\{$name};

interface I{$name}
{
    public function get(\$request,\$limit);
    public function show(\$model);
    public function store(\$model);
    public function update(\$model);
    public function delete(\$model);

}";
    }
}
