<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\programModel;
use App\Models\taskActivityModel;
use App\Traits\RestApi;
use Auth;

class ProgramController extends Controller
{
    use RestApi;

    public function __construct()
    { }

    public function listProgramTask()
    {
        $programModel = programModel::where('status', 1)->get();
        $ret = $programModel->pluck('program')->toArray();

        $array = [
            'progress' => $this->programProgress($ret),
            'point' => $this->getPoint(),
            'programs' => $ret
        ];
        return $this->output($array);
    }

    private function getPoint()
    {
        $user = Auth::user();
        return taskActivityModel::where('id_user', $user->id)->sum('point');
    }

    private function programProgress($program)
    {
        if (is_array($program) && (count($program) > 0)) {
            $totalProgram = count($program);
            $totalProgress = 0;
            foreach ($program as $p) {
                $totalProgress = $totalProgress + $p['progress'];
            }
            return $totalProgress / $totalProgram;
        }
        return 0;
    }
}