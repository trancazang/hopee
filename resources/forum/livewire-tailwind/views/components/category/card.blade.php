<div class="w-full h-full" style="{{ $category->styleVariables }}">
    <div class="bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-slate-800 dark:to-slate-700 border border-slate-200 dark:border-slate-700 shadow-md rounded-2xl p-5 flex flex-col gap-4 transition hover:shadow-lg hover:scale-[1.01] hover:ring-1 hover:ring-blue-400 h-full">
        {{-- Cột màu trái --}}
        <div class="w-1.5 rounded-full bg-indigo-500 dark:bg-indigo-400"></div>

        {{-- Nội dung chính --}}
        <div class="flex-1 flex flex-col gap-4">
            {{-- Tiêu đề + mô tả --}}
            <div>
                <h2 class="text-xl font-bold text-blue-700 dark:text-blue-400">
                    <a href="{{ $category->route }}" class="hover:underline">{{ $category->title }}</a>
                </h2>
                
                <p class="mt-2 text-slate-600 dark:text-slate-300 text-sm line-clamp-3">
                    {{ Str::limit($category->description, 100) }}
                </p>         
            </div>
            {{-- Dòng pill + newest thread --}}
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div class="flex gap-2">
                    <livewire:forum::components.pill
                        icon="chat-bubbles-mini"
                        :text="__('Chủ đề') . ': ' . $category->thread_count" />
                    <livewire:forum::components.pill
                        icon="chat-bubble-text-mini"
                        :text="__('Bài viết') . ': ' . $category->post_count" />
                </div>

                @if ($category->accepts_threads && $category->newestThread)
                    <div class="text-sm truncate text-right text-slate-700 dark:text-slate-300">
                        🗨 <a href="{{ $category->newestThread->route }}" class="text-blue-600 hover:underline">
                            {{ Str::limit($category->newestThread->title, 40) }}
                        </a>
                        <span class="ml-2 text-xs text-gray-500">
                            <livewire:forum::components.timestamp :carbon="$category->newestThread->created_at" />
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Subcategories --}}
    {{-- @if (count($category->children) > 0)
        @foreach ($category->children as $subcategory)
            <div class="mt-6 pl-6" style="{{ $subcategory->styleVariables }}">
                <div class="flex gap-4 bg-white dark:bg-slate-700 shadow-md rounded-lg p-4">
                    <div class="min-w-12 sm:min-w-24 text-slate-300 dark:text-slate-700 self-center text-center">
                        @include ('forum::components.icons.subcategory', ['size' => '12'])
                    </div>

                    <div class="flex-1 flex flex-col gap-2">
                        <div>
                            <h3 class="text-lg font-semibold text-category">
                                <a href="{{ $subcategory->route }}" class="hover:underline">{{ $subcategory->title }}</a>
                            </h3>
                            <p class="text-slate-600 text-sm dark:text-slate-400">{{ $subcategory->description }}</p>
                        </div>

                        <div class="flex flex-wrap justify-between items-center gap-4">
                            <div class="flex gap-2">
                                @if ($subcategory->accepts_threads)
                                    <livewire:forum::components.pill
                                        icon="chat-bubbles-mini"
                                        :text="__('Chủ đề') . ': ' . $subcategory->thread_count" />
                                    <livewire:forum::components.pill
                                        icon="chat-bubble-text-mini"
                                        :text="__('Bài viết') . ': ' . $subcategory->post_count" />
                                @endif
                            </div>

                            <div class="text-sm truncate text-right text-slate-700 dark:text-slate-300">
                                @if ($subcategory->newestThread)
                                    🗨 <a href="{{ $subcategory->newestThread->route }}" class="text-blue-600 hover:underline">
                                        {{ Str::limit($subcategory->newestThread->title, 40) }}
                                    </a>
                                    <span class="ml-2 text-xs text-gray-500">
                                        <livewire:forum::components.timestamp :carbon="$subcategory->newestThread->created_at" />
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif --}}
</div>
