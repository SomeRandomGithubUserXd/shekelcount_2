<?php

namespace App\Services\ImportEntries;

use App\Models\Entry;
use App\Models\Mutator;
use App\Models\Service\EntryImportBank;
use App\Models\User;
use App\Services\ImportEntries\Mutators\AbstractMutator;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

abstract class AbstractEntryParser
{
    public static array $allowedExtensions = ['csv', 'ods', 'xlsx', 'xls', 'xml', 'html'];

    protected string $filePath;

    protected int $userId;

    protected int $parserId;

    protected array $mutatorIds;

    /**
     * @throws Exception
     */
    public function __construct(UploadedFile $file, int $userId, int $parserId, array|null $mutatorIds)
    {
        $this->userId = $userId;
        $this->parserId = $parserId;
        $this->mutatorIds = $mutatorIds ?? [];
        $this->setFile($file);
    }

    public function __destruct()
    {
        Storage::disk('temp')->deleteDirectory($this->userId);
    }

    /**
     * @throws Exception
     */
    protected function setFile(UploadedFile $file): void
    {
        if (!in_array($file->clientExtension(), self::$allowedExtensions)) {
            throw new Exception("Unsupported file format");
        }
        $path = storage_path('app/temp/' . Storage::disk('temp')->put('/' . $this->userId, $file));
        $this->filePath = $path;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function toArray(): array
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $array = $spreadsheet->getActiveSheet()->toArray();
        $keys = $array[0];
        unset($array[0]);
        foreach ($array as $key => $entry) {
            $array[$key] = array_combine($keys, $entry);
        }
        return $array;
    }

    protected function getFilteredArray(): array
    {
        return $this->toArray();
    }

    abstract protected function toEntryEntity(array $entry): EntryEntity;

    public function getMutators(): array
    {
        $mutators = [];
        foreach ($this->mutatorIds as $mutatorId) {
            $mutators[] = Mutator::find($mutatorId);
        }
        return $mutators;
    }

    public function save(): void
    {
        $mutators = $this->getMutators();
        User::query()
            ->find($this->userId)
            ->entries()
            ->update(['was_recently_imported' => 0]);
        foreach ($this->getFilteredArray() as $entry) {
            $entry = $this->toEntryEntity($entry);
            foreach ($mutators as $mutator) {
                $entry->mutate($mutator);
            }
            $entryArray = $entry->toModelArray();
            $model = $entryArray + ['entry_import_bank_id' => $this->parserId];
            $entryArray['sum'] = $entryArray['sum'] * 100;
            Entry::firstOrCreate($entryArray, $model);
        }
        foreach ($mutators as $mutator) {
            $mutator
                ->getMutatorInstance()
                ->clearAfterMutation();
        }
    }

    public static function getImportMeta(): array
    {
        return [
            'options' => EntryImportBank::all(),
            'extensions' => self::$allowedExtensions,
        ];
    }
}
