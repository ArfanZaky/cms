<?php

namespace App\Services;

use App\Models\WebLogs;
use Illuminate\Support\Facades\Auth;

class LogServices
{
    public function handle($data)
    {
        $log = WebLogs::create([
            'admin_id' => isset(Auth::user()->id) ? Auth::user()->id : 0,
            'ip' => request()->ip(),
            'table_id' => $data['table_id'],
            'name' => $data['name'],
            'json' => $data['json'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $log;
    }
}
