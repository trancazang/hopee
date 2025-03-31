<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\ForumCategoriesRequest;
use App\Models\ForumCategories;

/**
 * Class ForumCategoriesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ForumCategoriesCrudController extends CrudController
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
        CRUD::setModel(ForumCategories::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/forum-categories');
        CRUD::setEntityNameStrings('forum categories', 'forum categories');
        
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        CRUD::column('id');
        CRUD::column('title')->label('Tiêu đề');
        CRUD::column('description')->label('Mô tả');
        CRUD::column('parent_id')->label('Danh mục cha');
        CRUD::column('accepts_threads')->type('boolean')->label('Cho phép Thread');
        CRUD::column('is_private')->type('boolean')->label('Riêng tư');

       
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {   CRUD::setValidation(\App\Http\Requests\ForumCategoriesRequest::class);
        CRUD::setValidation(ForumCategoriesRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        CRUD::field('title')->type('text')->label('Tiêu đề');
        CRUD::field('description')->type('textarea')->label('Mô tả');
        //CRUD::field('parent_id')->type('number')->label('Danh mục cha');
        CRUD::field('parent_id')
            ->type('select')
            ->label('Danh mục cha');

        CRUD::field('accepts_threads')->type('checkbox')->label('Cho phép Thread');
        CRUD::field('is_private')->type('checkbox')->label('Riêng tư');
        

        CRUD::field('color_light_mode')
            ->type('color')
            ->label('Color (light mode)')
            ->attributes(['type' => 'color']);

        CRUD::field('color_dark_mode')
            ->type('color')
            ->label('Color (dark mode)')
            ->attributes(['type' => 'color']);
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
