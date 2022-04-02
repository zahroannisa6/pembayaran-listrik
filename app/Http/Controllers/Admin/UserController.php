<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Requests\Admin\MassDestroyUserRequest;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, 'Forbidden');
        if($request->ajax()){
            $users = User::with('level')->get();
            return DataTables::of($users)
                    ->editColumn('level.level', function($row){
                        return "<span class='badge badge-pill badge-info'>". $row->level->level ."</span>";
                    })
                    ->addColumn('action', function($row){
                        $showGate       = 'user_show';
                        $editGate       = 'user_edit';
                        $deleteGate     = 'user_delete';
                        $crudRoutePart  = 'users';
                        
                        return view('partials.datatables-action', compact(
                            'showGate', 
                            'editGate', 
                            'deleteGate', 
                            'crudRoutePart',
                            'row',
                        ));
                    })
                    ->rawColumns(['level.level', 'action'])
                    ->toJson();
        }

        return view('pages.admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, 'Forbidden');
        $levels = Level::get();
        return view('pages.admin.user.create', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_level' => $request->id_level
        ]);
        return redirect()->route('admin.users.index')->withSuccess('Data user berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, 'Forbidden');
        return view('pages.admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, 'Forbidden');
        $levels = Level::get();
        return view('pages.admin.user.edit', compact('user', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        abort_if(Gate::denies('user_update'), Response::HTTP_FORBIDDEN, 'Forbidden');
        $request['password'] = bcrypt($request->password);
        $user->update($request->all());
        
        return redirect()->route('admin.users.index')->withSuccess('Data ' . $user->nama . ' berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, 'Forbidden');

        if(count($user->payments) > 0){
            alert()->error('User tidak bisa dihapus, karena mempunyai relasi dengan data pembayaran');
            return back();
        }

        $user->delete();
        return redirect()->route('admin.users.index')->withSuccess('Data ' . $user->nama . ' berhasil dihapus!');
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::whereIn('id', request('ids'))->get();
        foreach($users as $user){
            if(count($user->payments) > 0) {
                alert()->error('User tidak bisa dihapus, karena mempunyai relasi dengan data pembayaran');
                return;
            } elseif ($user->id_level !== 1) {
                $user->delete();
            } else {
                alert()->error('User Admin tidak dapat dihapus');
                return;
            }
            
        }

        return redirect()->route('admin.users.index')->withSuccess('Data user(s) berhasil dihapus!');
    }
}
