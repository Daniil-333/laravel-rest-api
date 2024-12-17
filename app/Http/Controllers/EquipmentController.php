<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Вывод пагинированного списка оборудования
     */
    public function index()
    {
        $equipment = Equipment::get();
        return response()->json($equipment);
    }

    /**
     * Создание новой(ых) записи(ей)
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Запрос данных по id
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Редактирование записи
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Удаление записи (мягкое удаление)
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Вывод пагинированного списка типов оборудования
     */
    public function showTypes()
    {

    }
}
