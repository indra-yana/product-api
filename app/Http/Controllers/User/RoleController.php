<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Validator;
use Auth;

class RoleController extends Controller {

    protected $tableNames;
    
    public function __construct() {
        $this->middleware('auth', ['except' => []]);
        $this->tableNames = config('permission.table_names');
    }

    public function index() {
        return view('role.index');
    }

    public function create() {
        return view('role.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:'.$this->tableNames['roles'].',name'
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('role.create')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> Please ensure to fill all fields correctly and re-submit the form.');
        }

        $role = Role::create(['name' => Str::slug($request->name, '-')]);
        if (!$role) {
            return redirect()
                    ->route('role.create')
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }

        return redirect()
                ->route('role.index')
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully saved.');
    }

    public function edit($id) {
        $role = Role::findOrFail($id);

        return view('role.edit', compact('role'));
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:'.$this->tableNames['roles'].',id',
            'name' => 'required|string|unique:'.$this->tableNames['roles'].',name,'.$request->id
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('role.edit', ['id' => $request->id])
                    ->withErrors($validator)
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> Please ensure to fill all fields correctly and re-submit the form.');
        }

        $role = Role::findOrFail($request->id);
        $role->name = Str::slug($request->name, '-');

        if (!$role->save()) {
            return redirect()
                    ->route('role.edit', ['id' => $request->id])
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }

        return redirect()
                ->route('role.index')
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully updated.');
    }

    public function delete(Request $request) {
        $role = Role::findOrFail($request->get('deleteID'));
        
        if (!$role->delete()) {
            return redirect()
                    ->route('role.index')
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }

        return redirect()
                ->route('role.index')
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully deleted.');
    }

    public function dtIndex() {
        return datatables()->of(Role::all())->addIndexColumn()->toJson();
    }

}
