<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\ForumthreadsRequest;

/**
 * Class ForumthreadsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ForumthreadsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Forumthreads::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/forumthreads');
        CRUD::setEntityNameStrings('forumthreads', 'forumthreads');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('title');
    
        CRUD::addColumn([
            'name' => 'category_id',
            'label' => 'Chuyên mục',
            'type' => 'select',
            'entity' => 'category',
            'model' => 'App\Models\ForumCategories',
            'attribute' => 'title',
        ]);
    
        CRUD::column('author_id');
        CRUD::column('pinned')->type('boolean');
        CRUD::column('locked')->type('boolean');
        CRUD::column('reply_count');
        CRUD::column('created_at');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
   
    protected function setupCreateOperation()
    {
        CRUD::setValidation(\App\Http\Requests\ForumthreadsRequest::class);

        CRUD::field('title');

        CRUD::addField([
            'label' => "Chuyên mục",
            'type' => 'select',
            'name' => 'category_id',
            'entity' => 'category',
            'model' => 'App\Models\ForumCategories',
            'attribute' => 'title',
        ]);

        CRUD::field('author_id')->type('number');
        CRUD::field('pinned')->type('checkbox');
        CRUD::field('locked')->type('checkbox');
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
}
