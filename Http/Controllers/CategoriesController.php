<?php

namespace Modules\Activos\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Activos\Entities\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Application|Factory|View|JsonResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            $business_id = auth()->user()->business_id;

            $category = Category::where('business_id', $business_id)
                ->select(['name', 'short_code', 'description', 'id']);

            return DataTables::eloquent($category)
                ->addColumn(
                    'action',
                    '<button data-href="{{route(\'categories.edit\', [$id])}}" class="btn btn-xs btn-primary edit_category_button"><i class="glyphicon glyphicon-edit"></i>  @lang("messages.edit")</button>
                        &nbsp;
                        <button data-href="{{action(\'categories.destroy\', [$id])}}" class="btn btn-xs btn-danger delete_category_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                        '
                )
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('activos::category.index')->with(compact('module_category_data', 'module_category_data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('activos::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('activos::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('activos::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
