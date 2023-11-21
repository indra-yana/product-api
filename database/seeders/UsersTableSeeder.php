<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            
            // Create role
            $roles = ['administrator', 'member'];

            foreach ($roles as $role) {
                Role::firstOrCreate(
                    ['name' => $role],
                    ['guard_name' => 'web']
                );
            }
            
            // Create permissions
            $permissions = [
                'dashboard-management',
                'trash_category-management',
                'trash_transaction-management',
                'users-management',
                'roles-management',
                'permissions-management',
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(
                    ['name' => $permission],
                    ['guard_name' => 'web']
                );
            }

            // Create users
            $users = [
                [
                    'id' => 1,
                    'name' => 'Administrator',
                    'email' => 'admin@laravel.com',
                    'roles' => ['administrator'],
                    'permissions' => [
                        'dashboard-management',
                        'trash_category-management',
                        'trash_transaction-management',
                        'users-management',
                        'roles-management',
                        'permissions-management',
                    ],
                ],
                [
                    'id' => 2,
                    'name' => 'Member One',
                    'email' => 'member@laravel.com',
                    'roles' => ['member'],
                    'permissions' => [
                        'dashboard-management',
                        'trash_transaction-management',
                    ],
                ],
                [
                    'id' => 3,
                    'name' => 'Member 2',
                    'email' => 'member2@laravel.com',
                    'roles' => ['member'],
                    'permissions' => [
                        'dashboard-management',
                        'trash_transaction-management',
                    ],
                ],
            ];

            foreach ($users as $user) {
                $usr = User::updateOrCreate(
                    ['id' => $user['id']],
                    ['name' => $user['name'], 'email' => $user['email'], 'password' => bcrypt('secret'), 'is_active' => 1]
                );

                $usr->syncRoles($user['roles']);
                $usr->syncPermissions($user['permissions']);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
