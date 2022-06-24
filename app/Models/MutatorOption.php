<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutatorOption extends Model
{
    protected $fillable = ['name', 'description', 'params', 'class'];

    protected $casts = [
        'params' => 'array',
    ];

    public function getMutatorOptionRulesAttribute(): array
    {
        $rules = [];
        foreach ($this->params as $param) {
            $rules[$param['name']] = $param['rules'];
        }
        return $rules;
    }
}
