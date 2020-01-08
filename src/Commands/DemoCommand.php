<?php

namespace Ajthinking\PHPFileManipulator\Commands;

use Illuminate\Console\Command;
use PHPFile;
use Ajthinking\PHPFileManipulator\Support\Exceptions\NotImplementedYetException;

class DemoCommand extends Command
{
    const PATH_TO_RESOURCES = 'packages/Ajthinking/PHPFileManipulator/src/Resources';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Provides a few interactive examples to illustrate php-file-manipulator capabilities';

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
     * @return mixed
     */
    public function handle()
    {
        $demos = [
            ['stats', 'Get some stats on your work'],
            ['relationships', 'Add missing relationship methods'],
            ['softdeletes', 'Use soft deletes on your User model'],
            ['installer', 'How to make an installer for your package'],
        ];

        $names = collect($demos)->map(function($demo) {
            return $demo[0];
        })->toArray();

        $this->table(['Demo', 'Description'], $demos);

        $method = $this->choice('Select a demo >>', $names , 0);
        
        if($method != 'stats') throw new NotImplementedYetException('Coming soon');
        $this->$method();
    }

    public function stats()
    {
        $files = PHPFile::in('')->get();
        
        $fileCount = $files->count();

        $charCount = $files->sum(function($file) {
            return strlen($file->contents);
        });

        $classes = $files->filter(function($file) {
            return $file->className();
        })->map(function($file) {
            return $file->className();
        });

        $classCount = $classes->unique()->count();

        $nonClassCount = $fileCount - $classCount;

        $extendingModel = PHPFile::where('classExtends', 'Model')->get()->count();

        $extendingController = PHPFile::where('classExtends', 'Controller')->get()->count();

        $this->table(["Stats (excluding vendor folder)"], [
            ["$fileCount PHP files."],
            ["$charCount characters worth of PHP code."],
            ["$classCount classes"],
            ["$extendingModel classes extending name 'Model'"],
            ["$extendingController classes extending name 'Controller'"],
            ["$nonClassCount non class files"],
        ]);
        $this->presentPathToSource();
    }

    public function presentPathToSource()
    {
        $this->line('');
        $this->line('Review source: ' .
            base_path('vendor/ajthinking/php-file-manipulator/src/Commands/DemoCommand.php')
        );
    }
}