<?php

namespace Modules\Member\Console;

use Illuminate\Console\Command;
use Modules\Member\Service\ValidateDataCSV;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Modules\Member\Http\Controllers\MemberController;

class BatchTestImportMember extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'batch:test_import_member';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        $validate = new ValidateDataCSV();
        app(MemberController::class)->importMember($validate);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            // ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
