<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\PostRequest;

/**
 * Class ForumPostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ForumPostCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ForumPost::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/forum-post');
        CRUD::setEntityNameStrings('forum post', 'forum posts');
        $this->crud->denyAccess(['create']);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::addColumn([
            'label' => 'Danh mục',
            'type' => 'select',
            'name' => 'category', // tên quan hệ ở model
            'entity' => 'category',
            'model' => 'App\Models\ForumCategory',
            'attribute' => 'title',
        ]);
        CRUD::addColumn([
            'name' => 'thread_id',
            'label' => 'Chủ đề',
            'type' => 'select',
            'entity' => 'thread',
            'model' => 'App\Models\Forumthreads',
            'attribute' => 'title',
        ]);
        
        CRUD::addColumn([
            'name' => 'author_id',
            'label' => 'Tác giả',
            'type' => 'select',
            'entity' => 'author',
            'model' => 'App\Models\User',
            'attribute' => 'name',
        ]);
        
        CRUD::column('sequence');
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
        CRUD::setValidation(PostRequest::class);

    // Chọn chủ đề (thread)
    CRUD::addField([
        'name' => 'thread_id',
        'label' => 'Chủ đề',
        'type' => 'select',
        'entity' => 'thread',
        'model' => 'App\Models\Forumthreads',
        'attribute' => 'title',
    ]);

    // Chọn người đăng (author)
    CRUD::addField([
        'name' => 'author_id',
        'label' => 'Tác giả',
        'type' => 'select',
        'entity' => 'author',
        'model' => 'App\Models\User',
        'attribute' => 'name',
    ]);

    // Nội dung bài viết
    CRUD::addField([
        'name' => 'content',
        'label' => 'Nội dung',
        'type' => 'textarea',
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
}
