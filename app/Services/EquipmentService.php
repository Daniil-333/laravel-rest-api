<?php

namespace App\Services;

use App\Models\Equipment;
use App\Validators\EquipmentValidatorStore;
use App\Validators\EquipmentValidatorUpdate;
use Illuminate\Support\Arr;

class EquipmentService
{
    private $data;

    private $validator;

    private $response;

    public function __construct($request, $typeValidator = 'store')
    {
        $this->data = $request;

        $data = match ($typeValidator) {
            'update' => $this->data,
            default => ['items' => $this->data],
        };

        $this->validator = match ($typeValidator) {
            'update' => new EquipmentValidatorUpdate($data),
            default => new EquipmentValidatorStore($data)
        };
    }

    public function validate()
    {
        $validateResult = $this->validator->validate();

        $this->prepareResponse($validateResult);

        return $this->response;
    }

    public function prepareResponse($validateResult)
    {
        if(Arr::exists($validateResult, 'errors')) {
            $this->response['errors'] = $validateResult['errors'];
        }

        if(Arr::exists($validateResult, 'success')) {
            if($this->validator instanceof EquipmentValidatorStore) {
                $this->create($validateResult);
            }
            elseif ($this->validator instanceof EquipmentValidatorUpdate) {
                $this->update($validateResult);
            }
        }
    }

    public function create($validateResult)
    {
        foreach($validateResult['success'] as $keyEquipment => $equipment) {
            $equipmentObj = Equipment::create($equipment);

            $this->response['success'][$keyEquipment] = $equipmentObj;
        }
    }

    public function update($validateResult)
    {
        if(!Arr::exists($validateResult, 'errors')) {
            $this->response['success'] = ['message' => 'Данные успешно обновлены'];
        }
    }
}
