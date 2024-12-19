<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Translation\PotentiallyTranslatedString;
use Closure;

class EquipmentSerialNumberMask implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     * @var array<string, mixed>
     */
    protected array $data = [];

    protected $equipmentTypes;
    protected $equipments;
    private $masks = [
        'N' => '[0-9]',
        'A' => '[A-Z]',
        'a' => '[a-z]',
        'X' => '[A-Z0-9]',
        'Z' => '[-_@]',
    ];

    /**
     * @param $equipmentTypes
     * @param $equipments
     */
    public function __construct($equipmentTypes, $equipments)
    {
        $this->equipmentTypes = $equipmentTypes;
        $this->equipments = $equipments;
    }

    /**
     * Run the validation rule.
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isArray = Str::startsWith($attribute, 'items');

        // ключ валидируемого объекта оборудования
        $key = $isArray ? Str::before(Str::after($attribute, '.'), '.') : null;
        // id типа оборудования валидируемого объекта оборудования
        $equipmentTypeId = $isArray ?
            $this->data['items'][$key]['equipment_type_id'] :
            $this->data['equipment_type_id'];

        if(Arr::exists($this->equipmentTypes, $equipmentTypeId)) {
            //проверяем $value по маске типа оборудования через регулярку
            $sourceMask = $this->equipmentTypes[$equipmentTypeId]['mask'];
            $sourceMaskExploded = str_split($sourceMask);
            $serialNumberExploded = str_split($value);

            foreach ($serialNumberExploded as $keySymbol => $symbol) {
                $symbolMask = $sourceMaskExploded[$keySymbol];
                $regExp = $this->masks[$symbolMask];

                $result = preg_match("/$regExp/", $symbol);
                if($result == 0) {
                    $fail(trim(
                        ($isArray ? "$key|" : '') . "Серийный номер не соответствует маске типа оборудования"
                        )
                    );
                    break;
                }
            }
        }

        //проверяем на уникальность составной индекс equipment_type_id + serial_number
        foreach ($this->equipments as $equipment) {
            if($value == $equipment['serial_number'] && $equipmentTypeId == $equipment['equipment_type_id']) {
                $fail(trim(
                    ($isArray ? "$key|" : '') . "Такой серийный номер в связке с типом оборудования уже есть в системе"
                    )
                );
                break;
            }
        }
    }

    /**
     * Set the data under validation.
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
