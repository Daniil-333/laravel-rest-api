<?php

namespace App\Validators;

use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

abstract class EquipmentValidator
{
    /**
     * Исходные данные, прижедшие в заросе
     * @var array
     */
    protected array $data;

    /**
     * Класс Валидатора
     * @var \Illuminate\Validation\Validator
     */
    protected \Illuminate\Validation\Validator $validator;

    /**
     * Типы оборудовпния
     * @var array
     */
    protected array $equipmentTypes;

    /**
     * Оборудование
     * @var array
     */
    protected array $equipments;

    public function __construct(array $data)
    {
        $this->data = Arr::exists($data, 'items') ? $data['items'] : $data;
        $this->equipmentTypes = EquipmentType::get()->keyBy('id')->toArray();
        $this->equipments = Equipment::get()->keyBy('id')->toArray();

        $this->validator = Validator::make(
            $data,
            $this->rules(),
            $this->messages()
        );
    }

    /**
     * Правила валидации
     * @return string[]
     */
    abstract public function rules(): array;

    /**
     * Сообщения об ошибках
     * @return string[]
     */
    abstract public function messages(): array;

    /**
     * Метод, возвращающий массив ошибок и данных, прошедших валидацию
     * @return array
     */
    abstract public function validate(): array;
}
