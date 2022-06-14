<?php

namespace App\Services\ImportEntries;

use App\Models\Entry;
use App\Models\Service\EntryImportBank;
use App\Models\User;
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

    /**
     * @throws Exception
     */
    public function __construct(UploadedFile $file, int $userId, int $parserId)
    {
        $this->userId = $userId;
        $this->parserId = $parserId;
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

    public function save(): void
    {
        User::find($this->userId)->entries()->update(['was_recently_imported' => 0]);
        foreach ($this->getFilteredArray() as $entry) {
            $entryArray = $this->toEntryEntity($entry)->toArray();
            $model = [
                ...$entryArray,
                'was_recently_imported' => 1,
                'entry_import_bank_id' => $this->parserId,
            ];
            $entryArray['sum'] = $entryArray['sum'] * 100;
            Entry::firstOrCreate($entryArray, $model);
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
