<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'mask',
    ];

    /**
     * Связь «элемент принадлежит» таблицы `equipment_types` с таблицей `equipment`
     * @return HasMany
     */
    public function equipmentType(): HasMany
    {
        return $this->hasMany(EquipmentType::class);
    }
}
