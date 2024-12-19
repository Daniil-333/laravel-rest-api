<?php

namespace App\Validators;

use App\Rules\EquipmentSerialNumberMask;

class EquipmentValidatorStore extends EquipmentValidator
{

    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.equipment_type_id' => 'required|exists:equipment_types,id',
            'items.*.serial_number' => [
                'required',
                new EquipmentSerialNumberMask($this->equipmentTypes, $this->equipments)
            ],
            'items.*.comment' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.equipment_type_id.required' => ':index|Не указан ID типа оборудования',
            'items.*.equipment_type_id.exists' => ':index|Не найден ID типа оборудования',
            'items.*.serial_number.required' => ':index|Не указан серийный номер оборудования',
        ];
    }

    public function validate(): array
    {
        $result = [];

        if($this->validator->fails()) {
            $errors = $this->validator->errors()->all();

            foreach ($errors as $error) {
                $errorArray = explode('|', $error);
                $result['errors'][$errorArray[0]][] = $errorArray[1];
            }
        }

        if(array_key_exists('errors', $result) && count($result['errors']) > 0) {
            $keysError = array_keys($result['errors']);
        }

        $validData = isset($keysError) ? $this->getValidateData($keysError) : $this->data;

        if($validData) {
            $result['success'] = $validData;
        }

        return $result;
    }

    /**
     * Метод, возвращающий массив данных, прошедших валидацию
     * @param $keysErrorObjects
     * @return array
     */
    public function getValidateData($keysErrorObjects): array
    {
        $result = [];
        foreach ($this->data as $key => $value) {
            if(!in_array($key, $keysErrorObjects))
                $result[$key] = $value;
        }

        return $result;
    }
}
