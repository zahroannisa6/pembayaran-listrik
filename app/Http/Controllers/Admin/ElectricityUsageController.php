<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsageRequest;
use App\Http\Requests\Admin\MassDestroyUsageRequest;
use App\Models\PlnCustomer;
use App\Models\Usage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resource Controller untuk model Usage
 */

class ElectricityUsageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies("usage_access"), Response::HTTP_FORBIDDEN, "Forbidden");

        if($request->ajax()){
            $usages = Usage::all();
            return DataTables::of($usages)
                    ->addColumn("action", function($row){
                        $showGate       = "usage_show";
                        $editGate       = "usage_edit";
                        $deleteGate     = "usage_delete";
                        $crudRoutePart  = "usages";
                        
                        return view("partials.datatables-action", compact(
                            "showGate", 
                            "editGate", 
                            "deleteGate",
                            "crudRoutePart",
                            "row",
                        ));
                    })
                    ->editColumn('bulan', function($usage) {
                        return Carbon::create(0, $usage->bulan)->monthName;
                    })
                    ->toJson();
        }
        return view("pages.admin.electricity-usage.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies("usage_create"), Response::HTTP_FORBIDDEN, "Forbidden");
        $customers = PlnCustomer::get();

        if($request->ajax()){
            $usage = Usage::where("id_pelanggan_pln", $request->id_pelanggan)->max("meter_akhir") ?? sprintf("%08d", 0);
            return response()->json(sprintf("%08d", $usage));
        }
        return view("pages.admin.electricity-usage.create", compact("customers"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UsageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsageRequest $request)
    {
        Usage::create($request->all());
        return redirect()->route("admin.usages.index")->withSuccess("Data berhasil ditambahkan!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usage  $usage
     * @return \Illuminate\Http\Response
     */
    public function show(Usage $usage)
    {
        abort_if(Gate::denies("usage_show"), Response::HTTP_FORBIDDEN, "Forbidden");
        return view("pages.admin.electricity-usage.show", compact("usage"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usage  $usage
     * @return \Illuminate\Http\Response
     */
    public function edit(Usage $usage)
    {
        abort_if(Gate::denies("usage_edit"), Response::HTTP_FORBIDDEN, "Forbidden");
        $customers = PlnCustomer::get();
        return view("pages.admin.electricity-usage.edit", compact("usage", "customers"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UsageRequest  $request
     * @param  \App\Models\Usage  $usage
     * @return \Illuminate\Http\Response
     */
    public function update(UsageRequest $request, Usage $usage)
    {
        abort_if(Gate::denies("usage_update"), Response::HTTP_FORBIDDEN, "Forbidden");
        $usage->update($request->all());
        return redirect()->route("admin.usages.index")->withSuccess("Data penggunaan berhasil diubah!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usage  $usage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usage $usage)
    {
        abort_if(Gate::denies("usage_delete"), Response::HTTP_FORBIDDEN, "Forbidden");

        /**
         * jika penggunaan ini memiliki relasi dengan tagihan,
         * maka soft deletes.
         */
        if($usage->bill_count > 0 && $usage->bill->status == "LUNAS"){
            alert('Data tidak dapat dihapus', 'Penggunaan memiliki relasi dengan tagihan yang telah terbayar', 'error');
            return redirect()->back();
        }
        $usage->bill->delete();
        $usage->delete();
        return redirect()->route('admin.usages.index')->withSuccess('Data penggunaan berhasil dihapus!');
    }

    public function massDestroy(MassDestroyUsageRequest $request)
    {
        abort_if(Gate::denies("usage_delete"), Response::HTTP_FORBIDDEN, "Forbidden");
        $usages = Usage::whereIn('id', request('ids'))->get();
        foreach ($usages as $usage) {
            if($usage->bill_count > 0 && $usage->bill->status == "LUNAS"){
                alert()->error('Data tidak dapat dihapus', 'Penggunaan memiliki relasi dengan tagihan yang telah terbayar');
                return;
            }
            
            $usage->bill->delete();
            $usage->delete();
        }

        return redirect()->back()->withSuccess('Data usage(s) berhasil dihapus!');
    }
}
