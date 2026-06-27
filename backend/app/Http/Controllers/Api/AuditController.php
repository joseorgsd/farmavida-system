<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\AuditLog;

class AuditController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | HISTORIAL AUDITORÍA
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $logs = AuditLog::with('user')

            ->latest()

            ->paginate(20);

        return response()->json(
            $logs
        );
    }
}