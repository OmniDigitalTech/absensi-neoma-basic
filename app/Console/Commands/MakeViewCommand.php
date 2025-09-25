<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {view}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new blade template.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $view = $this->argument('view'); // Retrieve the view argument

        $path = $this->viewPath($view);

        $this->createDir($path);

        if (File::exists($path)) {
            $this->error("File {$path} already exists!");
            return 1;
        }

        File::put($path, '');

        $this->info("File {$path} created.");
        return 0;
    }

    /**
     * Get the view full path.
     *
     * @param string $view
     *
     * @return string
     */
    public function viewPath($view)
    {
        $view = str_replace('.', '/', $view) . '.blade.php';

        $path = resource_path("views/{$view}");

        return $path;
    }

    /**
     * Create view directory if not exists.
     *
     * @param string $path
     */
    public function createDir($path)
    {
        $dir = dirname($path);

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0777, true);
        }
    }
}
