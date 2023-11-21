<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Validator;
use Auth;

class PermissionController extends Controller {

    protected $tableNames;
    
    public function __construct() {
        $this->tableNames = config('permission.table_names');
    }

    public function index() {
        return view('permission.index');
    }

    public function create() {
        return view('permission.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('permission.create')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> Please ensure to fill all fields correctly and re-submit the form.');
        }

        $permission = Permission::create(['name' => Str::slug($request->name, '-')]);
        if (!$permission) {
            return redirect()
                    ->route('permission.create')
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }

        return redirect()
                ->route('permission.index')
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully saved.');
    }

    public function edit($id) {
        $permission = Permission::findOrFail($id);

        return view('permission.edit', compact('permission'));
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:'.$this->tableNames['permissions'].',id',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('permission.edit', ['id' => $request->id])
                    ->withErrors($validator)
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> Please ensure to fill all fields correctly and re-submit the form.');
        }

        $permission = Permission::findOrFail($request->id);
        $permission->name = Str::slug($request->name, '-');
        if (!$permission->save()) {
            return redirect()
                    ->route('permission.edit', ['id' => $request->id])
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }

        return redirect()
                ->route('permission.index')
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully updated.');
    }

    public function delete(Request $request) {
        $permission = Permission::findOrFail($request->get('deleteID'));
        
        if (!$permission->delete()) {
            return redirect()
                    ->route('permission.index')
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }

        return redirect()
                ->route('permission.index')
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully deleted.');
    }

    public function dtIndex() {
        return datatables()->of(Permission::all())->addIndexColumn()->toJson();
    }

}
