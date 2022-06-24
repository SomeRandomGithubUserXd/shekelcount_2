<?php

namespace App\Console\Commands;

use App\Models\MutatorOption;
use App\Models\Service\EntryImportBank;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class SetupProjectCommand extends Command
{
    protected $signature = 'project:setup {--F|fresh}';

    protected $description = 'Prepares stuff that should be done for project to run properly.';

    public function handle()
    {
        $this->seedEntryImportBanksTable();
        $this->seedMutatorOptionsTable();
        $this->info("Done.");
    }

    protected function seedEntryImportBanksTable(): void
    {
        if($this->option('fresh')) EntryImportBank::truncate();
        foreach (config('entry_import.available_banks') as $bank) {
            $logo = $bank['logo'];
            unset($bank['logo']);
            $bank = EntryImportBank::firstOrCreate($bank);
            if (!$bank->logo) {
                $bank
                    ->copyMedia(resource_path($logo))
                    ->toMediaCollection(EntryImportBank::$logosMediaCollection);
            }
        }
    }

    protected function seedMutatorOptionsTable(): void
    {
        if($this->option('fresh')) MutatorOption::truncate();
        foreach (config('mutators') as $mutator) {
            try {
                MutatorOption::create($mutator);
            } catch (QueryException) {
                continue;
            }
        }
    }
}
