<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentCollection;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\EquipmentTypeCollection;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Services\EquipmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EquipmentController extends Controller
{
    /**
     * Вывод пагинированного списка оборудования
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $search = trim(htmlspecialchars(strip_tags($request->input('search'))));
        }

        $equipments = Equipment::when(
            isset($search),
            fn ($q) => $q
                ->where('serial_number', 'like', '%' . $search . '%')
                ->orWhere('comment', 'LIKE', '%' . $search . '%')
        )
            ->paginate(3);

        return new EquipmentCollection($equipments);
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
    public function showTypes(Request $request)
    {
        if ($request->has('search')) {
            $search = trim(htmlspecialchars(strip_tags($request->input('search'))));
        }

        $equipmentTypes = EquipmentType::when(
            isset($search),
            fn ($q) => $q
                ->where('title', 'like', '%' . $search . '%')
                ->orWhere('mask', 'LIKE', '%' . $search . '%')
        )
            ->paginate(2);

        return new EquipmentTypeCollection($equipmentTypes);
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
