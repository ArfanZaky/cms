<?php

namespace App\Http\Controllers\Engine\Wordings;

use App\Http\Controllers\Controller;
use App\Models\WebWording;
use App\Services\LogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WordingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(LogServices $LogServices, Request $request)
    {
        $this->LogServices = $LogServices;
        $this->request = $request;
    }

    public function index()
    {
        $data = WebWording::all();

        $language = languages();
        $env_lang = env('LANGUAGE_SETTINGS');
        $language = array_slice($language, 0, $env_lang);
        $language = array_map('strtolower', $language);

        $chunks = $data->chunk($env_lang);

        return view('engine.module.wording.index', compact('chunks', 'language'));
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
        request()->validate([
            'code' => 'required|unique:web_wordings',
        ]);

        $language = languages();
        $env_lang = env('LANGUAGE_SETTINGS');
        $language = array_slice($language, 0, $env_lang);
        $language = array_map('strtolower', $language);

        foreach ($language as $key => $value) {
            $data = new WebWording();
            $data->code = $this->slug($request->code);
            $data->language_id = $key + 1;
            $data->value = $request->$value;
            $data->save();

            $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Create Wording',
                'json' => json_encode($data),
            ]);
        }

        $json = $this->UpdateJson();

        if (! $json) {
            return redirect()->route('wordings')->with('error', 'Create Wordings Failed (JSON)');
        }

        return redirect()->route('wordings')->with('success', 'Create Wordings Success');
    }

    public function slug($z)
    {
        $z = strtolower($z);
        $z = str_replace(' ', '_', $z);

        return trim($z);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(WebWording $webWording)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(WebWording $webWording)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\WebWording  $webWording
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        request()->validate([
            'code' => 'required|unique:web_wordings,code,'.$request->code.',code',
            'values' => 'required',
        ]);

        $data = WebWording::where('code', $request->code)->get();

        foreach ($data as $key => $value) {
            $value->value = $request->values[$key];
            $value->save();

            $this->LogServices->handle([
                'table_id' => $value->id,
                'name' => 'Update Wording',
                'json' => json_encode($value),
            ]);
        }

        $json = $this->UpdateJson();

        if (! $json) {
            return response()->json([
                'status' => 'error',
                'message' => 'Update Wordings Failed (JSON)',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Update Wordings Success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebWording $webWording)
    {
        //
    }

    public function UpdateJson()
    {
        try {
            $wording = WebWording::get();
            $result = collect($wording)
                ->groupBy(function ($wordings) {
                    return code_lang()[$wordings->language_id - 1];
                })
                ->map(function ($grouped) {
                    return $grouped->pluck('value', 'code');
                })
                ->toJson();

            $directory = 'wording';
            if (! Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            Storage::disk('public')->put($directory.'/wording.json', $result);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
