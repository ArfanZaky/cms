<?php

namespace App\Http\Controllers\Engine\template;

use App\Http\Controllers\Controller;
use App\Models\WebTemplates;
use App\Services\LogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TemplatesController extends Controller
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = WebTemplates::all();

        return view('engine.module.template.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $data = new WebTemplates();
            $data->name = $request->name;
            $data->code = $request->code;
            $data->save();

            $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Add Template',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data Added successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WebBanners  $webBanners
     * @return \Illuminate\Http\Response
     */
    public function show(WebTemplates $WebTemplates, $id)
    {
        $data = WebTemplates::where('id', $id)->get();

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebBanners  $webBanners
     * @return \Illuminate\Http\Response
     */
    public function edit(WebTemplates $WebTemplates)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\WebBanners  $webBanners
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebTemplates $WebTemplates)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = WebTemplates::find($request->id);
            $data->name = $request->name;
            $data->code = $request->code;
            $data->save();
            $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Update Template',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data Updated successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to Update',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebBanners  $webBanners
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = WebTemplates::find($request->id);
            $data->delete();
            $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Delete Template',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data Deleted successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to Delete',
            ]);
        }
    }
}
