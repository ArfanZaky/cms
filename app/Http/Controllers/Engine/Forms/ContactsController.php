<?php

namespace App\Http\Controllers\Engine\Forms;

use App\Exports\FormExport;
use App\Http\Controllers\Controller;
use App\Models\WebContacts;
use App\Models\WebContent;
use App\Services\LogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ContactsController extends Controller
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    public function index(Request $request)
    {
        $data = WebContacts::all();

        $status = collect($data)->pluck('status_form')->unique()->values()->all();

        $product = WebContent::whereIn('visibility', [20, 7, 22, 23])->with(['translations', 'relation.article.translations'])
            ->orderBy('sort', 'asc')
            ->get();
        $product = collect($product)->map(function ($item) {
            $data = $item->getResponeses($item, 1);
            $data = collect($data)->toArray();

            return [
                'label' => $data['name'],
                'value' => $data['id'],
            ];
        })->values()->toArray();

        return view('engine.module.form.contact.index', compact('data', 'status', 'product'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $submit = $request->submit;
            if (strtolower($submit) != 'save') {
                $request->status_form = 'Closed';
            }

            $data = WebContacts::findOrFail($request->id);
            $logs = [
                'old' => $data->status_form,
                'user' => Auth::user()->name ?? 'System',
                'description' => $request->description ?? '',
                'new' => $request->status_form,
                'date' => date('Y-m-d H:i:s'),
            ];
            $merge = $data->logs ? collect($data->logs)->merge([$logs]) : [$logs];
            $data->logs = $merge;
            $data->status_form = $request->status_form;
            $data->save();
            DB::commit();
            $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Update Form Application',
                'json' => json_encode($data),
            ]);

            return redirect()->back()->with('success', 'Status Form Updated');
        } catch (\Exception $e) {
            DB::rollback();

            $this->LogServices->handle([
                'table_id' => 0,
                'name' => 'Log Error',
                'json' => $e,
            ]);

            return redirect()->back()->with('error', 'Status Form Failed to Update');
        }
    }

    public function logs($id)
    {
        $logs = WebContacts::findOrFail($id)?->logs;
        $logs = collect($logs)->sortByDesc('date')->values()->all();
        $views = view('engine.include.logs.form.index', compact('logs'))->render();

        return response()->json(['html' => $views]);
    }

    public function list(Request $request)
    {
        $data = WebContacts::with(['content.translations', 'branch.translations']);
        if ($request->start_date && $request->end_date) {
            $data = $data->when($request->start_date, function ($query, $start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })->when($request->end_date, function ($query, $end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            });
        }
        if ($request->status) {
            $data = $data->where('status_form', $request->status);
        }

        if ($request->product) {
            $data = $data->where('content_id', $request->product);
        }

        $data = $data->orderBy('created_at', 'desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return date('d M Y H:i', strtotime($row->created_at));
            })
            ->make(true);
    }

    public function export(Request $request)
    {
        return Excel::download(new FormExport($request), 'form-'.date('Y-m-d-H-i-s').'.xlsx');
    }
}
