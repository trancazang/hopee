<div>
    @include ('forum::components.loading-overlay')
    @include ('forum::components.breadcrumbs')

    <h1>{{ trans('forum::general.index') }}</h1>

    <div class="flex">
        <div class="grow">
        </div>
        <div>
            @can ('createCategories')
                <x-forum::link-button
                    :label="trans('forum::categories.create')"
                    icon="squares-plus-outline"
                    :href="Forum::route('category.create')" />
            @endcan
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($categories as $category)
            <div class="bg-gradient-to-br from-blue-50 to-white dark:from-slate-800 dark:to-slate-900 border border-transparent rounded-xl shadow-md p-4 hover:shadow-lg transition">
            <livewire:forum::components.category.card :$category :key="$category->id" />
            </div>
        @endforeach
    </div>
</div>
