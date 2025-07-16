<div id="post-{{ $post->sequence }}" class="post-card my-4" x-data="postCard" data-post="{{ $post->id }}" {{ $selectable ? 'x-on:change=onPostChanged' : '' }}>
    <div class="bg-white shadow-md rounded-lg flex flex-col items-start dark:bg-slate-700 {{ $post->trashed() ? 'opacity-65' : '' }}" :class="classes">
        <div class="p-6 w-full">
            {{-- Thông tin người đăng và thứ tự --}}
            @if ($showAuthorPane)
                <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">
                    <span class="font-semibold">{{ $post->authorName }}</span>
                    @if (! isset($single) || ! $single)
                        • <a href="{{ Forum::route('thread.show', $post) }}">#{{ $post->sequence }}</a>
                    @endif
                    • <livewire:forum::components.timestamp :carbon="$post->created_at" />
                </div>
            @endif

            {{-- Nếu có quote --}}
            @if (isset($post->parent))
                <livewire:forum::components.post.quote :post="$post->parent" />
            @endif

            {{-- Nội dung bài viết --}}
            <div class="dark:text-slate-100 mb-4">
                @if ($post->trashed())
                    @can ('viewTrashedPosts')
                        <div class="mb-4">
                            {!! Forum::render($post->content) !!}
                        </div>
                    @endcan

                    <div>
                        <livewire:forum::components.pill
                            bg-color="bg-zinc-400"
                            text-color="text-zinc-950"
                            margin="mr-2"
                            icon="trash-mini"
                            :text="trans('forum::general.deleted')" />
                    </div>
                @else
                    {!! Forum::render($post->content) !!}
                @endif
            </div>

            {{-- Các nút vote, báo cáo, checkbox --}}
            @php
                $userVote = \App\Models\ForumPostVote::where('post_id', $post->id)
                    ->where('user_id', auth()->id())
                    ->value('vote_type');

                $score = \App\Models\ForumPostVote::where('post_id', $post->id)
                    ->where('vote_type', 'upvote')->count()
                    - \App\Models\ForumPostVote::where('post_id', $post->id)
                    ->where('vote_type', 'downvote')->count();
            @endphp

            <div class="flex flex-wrap items-center gap-4 mt-2">
                <livewire:forum.components.vote-post
                    :post-id="$post->id"
                    :initial-score="$score"
                    :initial-vote="$userVote"
                />

                <livewire:forum.components.report-post :post-id="$post->id" />

                @if ($selectable)
                    <div class="inline-block ml-4">
                        <x-forum::form.input-checkbox
                            id=""
                            :value="$post->id"
                            @change="onChanged" />
                    </div>
                @endif
            </div>

            {{-- Liên kết và chỉnh sửa --}}
            <div class="mt-4 text-sm text-slate-500 dark:text-slate-400 flex flex-wrap gap-x-4">
                @if ($post->hasBeenUpdated())
                    <span>
                        {{ trans('forum::general.last_updated') }} <livewire:forum::components.timestamp :carbon="$post->updated_at" />
                    </span>
                @endif

                @if (!isset($single) || !$single)
                    @if (!$post->trashed())
                        <a href="{{ Forum::route('post.show', $post) }}" class="font-medium">{{ trans('forum::general.permalink') }}</a>
                        @can ('edit', $post)
                            <a href="{{ Forum::route('post.edit', $post) }}" class="font-medium ml-2">{{ trans('forum::general.edit') }}</a>
                        @endcan
                        @can ('reply', $post->thread)
                            <a href="{{ Forum::route('thread.reply', $post->thread) }}?parent_id={{ $post->id }}" class="font-medium ml-2">{{ trans('forum::general.reply') }}</a>
                        @endcan
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>


@script
<script>
Alpine.data('postCard', () => {
    return {
        classes: 'outline-none',
        onChanged(event) {
            event.stopPropagation();

            if (event.target.checked) {
                this.classes = 'outline outline-blue-500';
            } else {
                this.classes = 'outline-none';
            }

            $dispatch('change', { isSelected: event.target.checked, id: event.target.value });
        }
    }
});
</script>
@endscript