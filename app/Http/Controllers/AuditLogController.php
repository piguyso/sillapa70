<?php

namespace App\Http\Controllers;

use App\AuditLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** หน้าดู log "ใครทำอะไร" (เฉพาะผู้ดูแลระบบ) */
class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->input('q')),
            'user' => trim((string) $request->input('user')),
            'action' => in_array($request->input('action'), array_keys(AuditLog::ACTIONS), true) ? $request->input('action') : '',
            'table' => trim((string) $request->input('table')),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'scope' => $request->input('scope') === 'all' ? 'all' : 'event',
        ];

        $query = AuditLog::query()->orderByDesc('id');

        if ($filters['scope'] === 'event' && active_event_id()) {
            $query->where('event_id', active_event_id());
        }
        if ($filters['user'] !== '') {
            $query->where(fn ($q) => $q->where('username', 'like', '%'.$filters['user'].'%')
                ->orWhere('user_id', ctype_digit($filters['user']) ? (int) $filters['user'] : 0));
        }
        if ($filters['action'] !== '') {
            $query->where('action', $filters['action']);
        }
        if ($filters['table'] !== '') {
            $query->where('subject_table', $filters['table']);
        }
        if ($filters['q'] !== '') {
            $like = '%'.$filters['q'].'%';
            $query->where(fn ($q) => $q->where('description', 'like', $like)->orWhere('url', 'like', $like)->orWhere('subject_id', $filters['q']));
        }
        if ($filters['from']) {
            $query->where('created_at', '>=', $filters['from'].' 00:00:00');
        }
        if ($filters['to']) {
            $query->where('created_at', '<=', $filters['to'].' 23:59:59');
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('audit.index', [
            'logs' => $logs,
            'filters' => $filters,
            'actions' => AuditLog::ACTIONS,
            'tables' => AuditLog::TABLES,
            'positions' => [0 => 'โรงเรียน', 1 => 'ศูนย์', 5 => 'เครือข่าย', 10 => 'ผู้ดูแลระบบ'],
            'total' => DB::table('audit_logs')->count(),
        ]);
    }
}
