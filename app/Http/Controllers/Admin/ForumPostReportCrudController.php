<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;


/**
 * Class ForumPostReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ForumPostReportCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ForumPostReport::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/forum-post-report');
        CRUD::setEntityNameStrings('báo cáo', 'các báo cáo');
        $this->crud->denyAccess(['create','update','delete']);

    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('user_id')->label('Người báo cáo')
        ->type('select')->entity('user')->attribute('name');

        CRUD::addColumn([
            'name' => 'post_id',
            'label' => 'Bài viết',
            'type' => 'closure',
            'function' => function ($entry) {
                $post = $entry->post;
                $thread = $post->thread;
        
                $slug = Str::slug($thread->title);
                $url = url("/forum/t/{$thread->id}-{$slug}/p/{$post->id}");
        
                return '<a href="' . $url . '" target="_blank">Xem bài viết #' . $post->id . '</a>';
            },
            'escaped' => false,
        ]);

    CRUD::column('reason')->label('Lý do');
    CRUD::addColumn([
        'name' => 'status',
        'label' => 'Trạng thái',
        'type' => 'closure',
        'function' => function ($entry) {
            $statuses = [
                'pending' => 'Chờ duyệt',
                'reviewed' => 'Đã xử lý',
                'rejected' => 'Từ chối',
            ];
    
            $current = $entry->status;
            $html = "<select onchange=\"updateStatus(this, {$entry->id})\" class='form-select bg-white border rounded px-2 py-1 text-sm'>";
            foreach ($statuses as $value => $label) {
                $selected = $current === $value ? 'selected' : '';
                $html .= "<option value='{$value}' {$selected}>{$label}</option>";
            }
            $html .= "</select>";
            return $html;
        },
        'escaped' => false,
    ]);
    CRUD::column('created_at')->label('Thời gian báo cáo');
    
    if (request()->has('status') && request()->status != '') {
        CRUD::addClause('where', 'status', request()->status);
    }
    
    }
    

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::denyAccess('create'); // Không cho tạo mới thủ công
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::field('status')->label('Trạng thái')->type('select_from_array')->options([
            'pending' => 'Chờ duyệt',
            'reviewed' => 'Đã xử lý',
            'rejected' => 'Từ chối',
        ])->default('pending');
    }
    protected function setupDeleteOperation()
    {
        // Admin vẫn có thể xoá báo cáo nếu cần
    }
}
