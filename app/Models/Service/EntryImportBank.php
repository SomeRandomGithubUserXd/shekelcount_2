<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EntryImportBank extends Model implements HasMedia
{
    use InteractsWithMedia;

    public static string $logosMediaCollection = 'LOGOS_MEDIA_COLLECTION';

    protected $fillable = ['name', 'parser'];

    protected $appends = ['logo'];

    public function getLogoAttribute(): string|null
    {
        try {
            $logo = $this->getMedia(self::$logosMediaCollection)->first()->original_url;
        } catch (\Exception $exception) {
            $logo = null;
        }
        return $logo;
    }
}
