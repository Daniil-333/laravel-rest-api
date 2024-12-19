<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentCollection;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\EquipmentTypeCollection;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Services\EquipmentService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EquipmentController extends Controller
{
    /**
     * Вывод пагинированного списка оборудования
     */
    public function index()
    {
        return new EquipmentCollection(Equipment::paginate(3));
    }

    /**
     * Создание новой(ых) записи(ей)
     */
    public function store(Request $request)
    {
        $response = (new EquipmentService($request->all()))->validate();

        return response()->json($response, 201);
    }

    /**
     * Запрос данных по id
     */
    public function show(string $id)
    {
        $equipment = Equipment::find($id);

        if(!$equipment) {
            return $this->returnResponseIfNotExist();
        }

        return new EquipmentResource($equipment);
    }

    /**
     * Редактирование записи
     */
    public function update(Request $request, string $id)
    {
        $equipment = Equipment::find(trim(strip_tags(htmlspecialchars($id))));

        if(!$equipment) {
            return $this->returnResponseIfNotExist();
        }

        $response = (new EquipmentService($request->all(), 'update'))->validate();

        if(!Arr::exists($response, 'errors')) {
            $equipment->update($request->all());
        }

        return response()->json($response);
    }

    /**
     * Удаление записи (мягкое удаление)
     */
    public function destroy(string $id)
    {
        $equipment = Equipment::find($id);

        if(!$equipment) {
            return $this->returnResponseIfNotExist();
        }

        $equipment->delete();

        return response()->json([
            'id' => $id,
            'success' => 'Оборудование успешно удалено',
        ], 204);
    }

    /**
     * Вывод пагинированного списка типов оборудования
     */
    public function showTypes()
    {
        return new EquipmentTypeCollection(EquipmentType::paginate(3));
    }

    /**
     * Возврат, если ресурс не найден
     * @return JsonResponse
     */
    public function returnResponseIfNotExist(): JsonResponse
    {
        return response()->json([
            'message' => 'Оборудование не найдено'
        ], 404);
    }
}
