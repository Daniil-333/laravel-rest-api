<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'comment',
    ];

    /**
     * Связь «один ко многим» таблицы `equipment` с таблицей `equipment_types`
     * @return BelongsTo
     */
    public function equipments(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
