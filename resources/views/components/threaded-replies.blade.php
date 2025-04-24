@props(['posts'])

@foreach ($posts as $post)
  <div class="ml-{{ $post->depth * 4 }} mt-2 border-l border-slate-300 pl-3" x-data="{ open: false }">
    <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-sm">
      <div class="flex justify-between items-center">
        <div class="text-sm text-slate-700 dark:text-slate-100 font-semibold">
          {{ $post->authorName }}
          <span class="text-xs text-gray-400 font-normal ml-2">#{{ $post->sequence }}</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
          <button @click="open = !open" class="text-xs text-blue-600 hover:underline focus:outline-none">
            <span x-show="open">Ẩn phản hồi</span>
            <span x-show="!open">Hiện phản hồi</span>
          </button>
        </div>
      </div>

      <div class="mt-2 text-sm text-slate-700 dark:text-slate-100">
        {!! Forum::render($post->content) !!}
      </div>

      <div class="flex gap-3 mt-2 text-xs text-slate-500">
        <a href="{{ Forum::route('post.show', $post) }}" class="hover:underline">Permalink</a>
        @can ('edit', $post)
          <a href="{{ Forum::route('post.edit', $post) }}" class="hover:underline">Chỉnh sửa</a>
        @endcan
        @can ('reply', $post->thread)
          <a href="{{ Forum::route('thread.reply', $post->thread) }}?parent_id={{ $post->id }}" class="hover:underline">Phản hồi</a>
        @endcan
      </div>
    </div>

    <div x-show="open" x-transition>
      @if ($post->children)
        <x-threaded-replies :posts="$post->children" />
      @endif
    </div>
  </div>
@endforeach
