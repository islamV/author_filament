<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeController extends Command
{

    protected $signature = 'app:make-controller {name} {entity}';
    protected $description = 'this command to make controller class';

    public function handle()
    {
        $name = $this->argument('name');
        $entity = $this->argument('entity');
        $controllerPath = app_path("Http/Controllers/v1/{$entity}/{$name}/{$name}Controller.php");
        Artisan::call('make:controller', ['name' => "v1/{$entity}/{$name}/{$name}Controller"]);
        File::put($controllerPath, $this->putControllerContent($name,$entity));
        $this->info('Controller created successfully.');
    }

    public function putControllerContent($name,$entity): string
    {
        $lower = strtolower($name);
        return "<?php

namespace App\Http\Controllers\\v1\\{$entity}\\{$name};

use App\Http\Controllers\Controller;
use App\Http\Requests\\v1\\{$entity}\\{$name}\Store{$name}Request;
use App\Http\Requests\\v1\\{$entity}\\{$name}\Update{$name}Request;
use App\Http\Resources\\v1\\{$entity}\\{$name}\\{$name}Resource;
use App\Models\{$name};
use App\Services\\v1\\{$entity}\\{$name}\\{$name}Service;
use Illuminate\Http\Request;

class {$name}Controller extends Controller
{
    private {$name}Service \${$lower}Service;
    public function __construct({$name}Service \${$lower}Service)
    {
        \$this->{$lower}Service = \${$lower}Service;
    }
    public function index(Request \$request)
    {
        return \$this->returnData(__('messages.{$lower}.list'),200,
            {$name}Resource::collection(\$this->{$lower}Service->index(\$request,\$request->get('per_page',10))));
    }
    public function store(Store{$name}Request \$request)
    {
        \$this->{$lower}Service->store(\$request);
        return \$this->success(__('messages.{$lower}.added'),200);
    }
    public function show({$name} \${$lower})
    {
        return \$this->returnData(__('messages.{$lower}.details'),200,
            new {$name}Resource(\$this->{$lower}Service->show(\${$lower})));
    }
    public function update(Update{$name}Request \$request, {$name} \${$lower})
    {
        \$this->{$lower}Service->update(\$request,\${$lower});
        return \$this->success(__('messages.{$lower}.updated'),200);
    }
    public function destroy({$name} \${$lower})
    {
        \$this->{$lower}Service->delete(\${$lower});
        return \$this->success(__('messages.{$lower}.deleted'),200);
    }
}
";
    }
}
