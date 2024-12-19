<?php

namespace App\Validators;

use App\Rules\EquipmentSerialNumberMask;
use Illuminate\Support\Arr;

class EquipmentValidatorUpdate extends EquipmentValidator
{

    public function rules(): array
    {
        return [
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'serial_number' => [
                'required',
                new EquipmentSerialNumberMask($this->equipmentTypes, $this->equipments)
            ],
            'comment' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'equipment_type_id.required' => 'Не указан ID типа оборудования',
            'equipment_type_id.exists' => 'Не найден ID типа оборудования',
            'serial_number.required' => 'Не указан серийный номер оборудования',
        ];
    }

    public function validate(): array
    {
        $result = [];

        if($this->validator->fails()) {
            $result['errors'] = $this->validator->errors()->all();
        }
        else {
            $result['success'] = $this->data;
        }

        return $result;
    }
}
