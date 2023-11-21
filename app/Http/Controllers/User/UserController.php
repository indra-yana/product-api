<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $tableNames;

    public function __construct()
    {
        $this->tableNames = config('permission.table_names');
    }

    public function index()
    {
        return view('user.index');
    }

    public function create() {
        return view('user.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email',
            'password' => 'string|required|min:6',
            'password2' => 'string|required|min:6|same:password',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('user.create')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> Please ensure to fill all fields correctly and re-submit the form.');
        }

        $user = new User();
        $user->name = ucwords($request->name);
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        if (!$user->save()) {
            return redirect()
                    ->route('user.create')
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }

        return redirect()
                ->route('user.index')
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully saved.');
    }

    public function edit($id) {
        $user = User::findOrFail($id);

        return view('user.edit', compact('user'));
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:users,id',
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email,'.$request->id,
            'password' => $request->password ? 'string|required|min:6' : '',
            'password2' => $request->password ? 'string|required|min:6|same:password' : '',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                    ->route('user.edit', ['id' => $request->id])
                    ->withErrors($validator)
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> Please ensure to fill all fields correctly and re-submit the form.');
        }

        $user = User::findOrFail($request->id);
        $user->name = ucwords($request->name);
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        if (!$user->save()) {
            return redirect()
                    ->route('user.edit', ['id' => $request->id])
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }
        
        return redirect()
                ->route('user.index')
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully updated.');
    }

    public function delete(Request $request) {
        $user = User::findOrFail($request->get('deleteID'));
        
        if (!$user->delete()) {
            return redirect()
                    ->route('user.index')
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }

        return redirect()
                ->route('user.index')
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully deleted.');
    }

    public function activateOrInactivate(Request $request) {
        $validator = Validator::make($request->all(), [
            'paramID_user' => 'required|numeric|exists:users,id',
            'paramID_status' => 'required|string|in:Activate,Inactivate',
        ]);
        
        $user = User::findOrFail($request->paramID_user);
        $user->is_active = $user->is_active ? 0 : 1;

        if (!$user->save()) {
            return redirect()
                    ->route('user.index')
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> There\'s something wrong with the system. Please contact administrator about this.');
        }

        return redirect()
                ->route('user.index')
                ->with('type', 'success')
                ->with('message', "<b>Well Done!</b> The data has been successfully $request->paramID_status.");
    }

    public function dtIndex() {
        return datatables()->of(User::query()->get())->addIndexColumn()->toJson();
    }


    /*
    |--------------------------------------------------------------------------
    | User Permissions
    |--------------------------------------------------------------------------
    */
    public function permission($id) { 
        $user = User::findOrFail($id);
        $permissions = Permission::whereDoesntHave('users', function($query) use($user){
            $query->where('model_id', $user->id);
        })->get();

        return view('user.permission', compact('user', 'permissions'));
    }

    public function storePermission(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|numeric|exists:users,id',
            'id_permission' => 'required|array',
            'id_permission.*' => 'numeric|exists:'.$this->tableNames['permissions'].',id',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('user.permission', ['id' => $request->id_user])
                    ->withErrors($validator, 'form')
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> Please ensure to fill all fields correctly and re-submit the form.');
        }

        $user = User::findOrFail($request->id_user);
        $user->givePermissionTo($request->id_permission);

        return redirect()
                ->route('user.permission', ['id' => $request->id_user])
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully saved.');
    }

    public function deletePermission(Request $request) {
        $user = User::findOrFail($request->paramID_user);
        $user->revokePermissionTo($request->paramID_permission);

        return redirect()
                ->route('user.permission', ['id' => $request->paramID_user])
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully deleted.');
    } 

    public function dtIndexPermission(Request $request) {
        return datatables()->of(User::find($request->paramID)->permissions)->addIndexColumn()->toJson();
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | User Role
    |--------------------------------------------------------------------------
    */
    public function role($id) {
        $user = User::findOrFail($id);
        $roles = Role::whereDoesntHave('users', function($query) use($user){
            $query->where('model_id', $user->id);
        })->get();

        return view('user.role', compact('user', 'roles'));
    }

    public function storeRole(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|numeric|exists:users,id',
            'id_role' => 'required|numeric|exists:'.$this->tableNames['roles'].',id',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('user.role', ['id' => $request->id_user])
                    ->withErrors($validator, 'form')
                    ->withInput()
                    ->with('type', 'danger')
                    ->with('message', '<b>Oh!</b> Please ensure to fill all fields correctly and re-submit the form.');
        }

        $user = User::findOrFail($request->id_user);
        $user->assignRole($request->id_role);

        return redirect()
                ->route('user.role', ['id' => $request->id_user])
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully saved.');
    }

    public function deleteRole(Request $request) {
        $user = User::findOrFail($request->paramID_user);
        $user->removeRole($request->paramID_role);

        return redirect()
                ->route('user.role', ['id' => $request->paramID_user])
                ->with('type', 'success')
                ->with('message', '<b>Well Done!</b> The data has been successfully deleted.');
    } 

    public function dtIndexRole(Request $request) {
        return datatables()->of(User::find($request->paramID)->roles)->addIndexColumn()->toJson();
    }

}
