<?php

namespace App\Console\Commands;

use App\Models\Service\EntryImportBank;
use Illuminate\Console\Command;

class SetupProjectCommand extends Command
{
    protected $signature = 'project:setup';

    protected $description = 'Prepares stuff that should be done for project to run properly.';

    public function handle()
    {
        $this->seedEntryImportBanksTable();
        $this->info("Done.");
    }

    protected final function seedEntryImportBanksTable(): void
    {
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
}
