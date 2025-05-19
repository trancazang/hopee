<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\UserRequest;


/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {   $this->crud->setValidation(UserRequest::class);
        CRUD::setFromDb(); // set columns from db columns.
        $this->crud->addColumn([
            'name' => 'role',
            'label' => 'Role',
            'type' => 'text',
        ]);
        

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
{
    CRUD::setValidation(UserRequest::class);
    CRUD::addField([
        'name' => 'name',
        'label' => 'Tên người dùng',
        'type' => 'text',
    ]);

    CRUD::addField([
        'name' => 'email',
        'label' => 'Email',
        'type' => 'email',
    ]);

    CRUD::addField([
        'name' => 'password',
        'label' => 'Mật khẩu',
        'type' => 'password',
    ]);

    CRUD::addField([
        'name' => 'role',
        'label' => 'Vai trò',
        'type' => 'select_from_array',
        'options' => [
            'admin' => 'Admin',
            'moderator' => 'Moderator',
            'user' => 'User',
        ],
        'allows_null' => false,
        'default' => 'user',
    ]);
}


    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if (isset($user->password) && $user->isDirty('password')) {
                $user->password = \Hash::make($user->password);
            }
        });
    }

    
}
