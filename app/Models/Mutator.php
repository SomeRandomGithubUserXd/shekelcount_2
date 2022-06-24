<?php

namespace App\Models;

use App\Services\ImportEntries\Mutators\AbstractMutator;
use App\Traits\StringUtils;
use Illuminate\Database\Eloquent\Model;

class Mutator extends Model
{
    use StringUtils;

    protected $fillable = ['user_id', 'name', 'mutator_option_id', 'arguments', 'option'];

    protected $casts = [
        'arguments' => 'array',
    ];

    public function getMutatorInstance(): AbstractMutator
    {
        $arguments = [];
        foreach ($this->arguments as $argumentName => $value) {
            $arguments[$this->snakeToCamelCase($argumentName)] = $value;
        }
        return new $this->mutatorOption->class(auth()->id(), ...$arguments);
    }

    public function getTransformVisualisation(): array
    {
        return $this
            ->getMutatorInstance()
            ->getVisualisation()
            ->toArray();
    }

    public function getAdditionalInfo(): array
    {
        return $this
            ->getMutatorInstance()
            ->getAdditionalInfo();
    }

    public function mutatorOption()
    {
        return $this->belongsTo(MutatorOption::class);
    }
}
