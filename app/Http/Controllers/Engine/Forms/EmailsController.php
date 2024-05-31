<?php

namespace App\Http\Controllers\Engine\Forms;

use App\Http\Controllers\Controller;
use App\Models\WebArticleCategories;
use App\Models\WebEmail;
use App\Services\LogServices;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailsController extends Controller
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
        $email = WebEmail::with(['category.translations', 'branch.translations'])->orderBy('id', 'desc')->get();

        // dd($email->toArray());
        return view('engine.module.form.email.index', compact('email'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product_category = WebArticleCategories::whereIn('visibility', [19, 33])->with(['translations', 'relation.article.translations'])
            ->orderBy('sort', 'asc')
            ->get();
        $language = 1;
        $category_product = $product_category->where('visibility', 33)->where('parent', '!=', 0)->values();
        $category_product = collect($category_product)->map(function ($item, $keys) use ($language) {
            $data = $item->getResponeses($item, $language);
            $data = collect($data)->toArray();

            return [
                'label' => ($item->parent == 7) ? \App\Helper\Helper::_wording('personal', $language).' - '.$data['name'] : \App\Helper\Helper::_wording('corporate', $language).' - '.$data['name'],
                'value' => $data['id'],
            ];

        })->sortByDesc('label')->values()->toArray();

        $category_product = collect($category_product)->values()->toArray();

        // branch
        $branch = $product_category->where('visibility', 19)->first()->relation->values()->filter(function ($item) {
            return $item->article != null;
        })->values();
        $branch = collect($branch)->map(function ($item, $key) use ($language) {
            $article = $item->article->getResponeses($item->article, $language);
            $article = collect($article)->toArray();

            return [
                'label' => $article['name'],
                'value' => $article['id'],
            ];
        })->toArray();

        return view('engine.module.form.email.create', compact('category_product', 'branch'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        if (empty($request->branch) && empty($request->category_id)) {
            return redirect()->back()->with('error', 'Please select Product or Branch');
        }

        if (! empty($request->branch) && ! empty($request->category_id)) {
            return redirect()->back()->with('error', 'Please select Product or Branch');
        }

        DB::beginTransaction();
        try {
            $email = WebEmail::create([
                'id_category' => $request->category_id,
                'id_branch' => $request->branch,
                'email' => $request->email,
            ]);
            DB::commit();

            return redirect()->route('form.email')->with('success', 'Email created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->back()->with('error', 'Email created failed.');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show(User $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_category = WebArticleCategories::whereIn('visibility', [19, 33])->with(['translations', 'relation.article.translations'])
            ->orderBy('sort', 'asc')
            ->get();
        $language = 1;
        $category_product = $product_category->where('visibility', 33)->where('parent', '!=', 0)->values();
        $category_product = collect($category_product)->map(function ($item, $keys) use ($language) {
            $data = $item->getResponeses($item, $language);
            $data = collect($data)->toArray();

            return [
                'label' => ($item->parent == 7) ? \App\Helper\Helper::_wording('personal', $language).' - '.$data['name'] : \App\Helper\Helper::_wording('corporate', $language).' - '.$data['name'],
                'value' => $data['id'],
            ];

        })->sortByDesc('label')->values()->toArray();

        $category_product = collect($category_product)->values()->toArray();

        // branch
        $branch = $product_category->where('visibility', 19)->first()->relation->values()->filter(function ($item) {
            return $item->article != null;
        })->values();
        $branch = collect($branch)->map(function ($item, $key) use ($language) {
            $article = $item->article->getResponeses($item->article, $language);
            $article = collect($article)->toArray();

            return [
                'label' => $article['name'],
                'value' => $article['id'],
            ];
        })->toArray();

        $email = WebEmail::with(['category.translations', 'branch.translations'])->find($id);

        return view('engine.module.form.email..edit', compact('email', 'category_product', 'branch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        if (empty($request->branch) && empty($request->category_id)) {
            return redirect()->back()->with('error', 'Please select Product or Branch');
        }

        if (! empty($request->branch) && ! empty($request->category_id)) {
            return redirect()->back()->with('error', 'Please select Product or Branch');
        }

        DB::beginTransaction();
        try {
            $email = WebEmail::find($id);
            $email->id_category = $request->category_id;
            $email->id_branch = $request->branch;
            $email->email = $request->email;
            $email->save();
            DB::commit();

            return redirect()->route('form.email')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('form.email')->with('error', 'User updated failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $email = WebEmail::find($id);
            $email->delete();
            DB::commit();

            return redirect()->route('form.email')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('form.email')->with('error', 'User deleted failed.');
        }
    }
}
