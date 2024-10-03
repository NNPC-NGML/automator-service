<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\AutomatorTaskService;
use App\Http\Resources\AutomatorTaskResource;

class AutomatorTaskController extends Controller
{

    private AutomatorTaskService $automatorTaskService;

    public function __construct(AutomatorTaskService $automatorTaskService)
    {
        $this->automatorTaskService = $automatorTaskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function getHeadOfUnitAssignableTask()
    {
        $userId = auth()->id();
        $task = $this->automatorTaskService->getTaskWithUserId($userId);
        return AutomatorTaskResource::collection($task)->additional(['status' => 'success']);
    }

    public function getHeadOfUnitSubordinate()
    {
        //
    }
    public function assignTaskToHeadOfUnitSubordinate()
    {
        //
    }
}
