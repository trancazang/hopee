@basset('https://unpkg.com/jquery@3.6.1/dist/jquery.min.js')
@basset('https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js')
@basset('https://unpkg.com/noty@3.2.0-beta-deprecated/lib/noty.min.js')
@basset('https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js')

@if (backpack_theme_config('scripts') && count(backpack_theme_config('scripts')))
    @foreach (backpack_theme_config('scripts') as $path)
        @if(is_array($path))
            @basset(...$path)
        @else
            @basset($path)
        @endif
    @endforeach
@endif

@if (backpack_theme_config('mix_scripts') && count(backpack_theme_config('mix_scripts')))
    @foreach (backpack_theme_config('mix_scripts') as $path => $manifest)
        <script type="text/javascript" src="{{ mix($path, $manifest) }}"></script>
    @endforeach
@endif

@if (backpack_theme_config('vite_scripts') && count(backpack_theme_config('vite_scripts')))
    @vite(backpack_theme_config('vite_scripts'))
@endif

@include(backpack_view('inc.alerts'))

@if(config('app.debug'))
    @include('crud::inc.ajax_error_frame')
@endif

@push('after_scripts')
    @basset(base_path('vendor/backpack/crud/src/resources/assets/js/common.js'))
@endpush
<script>
    function updateStatus(select, reportId) {
        fetch("/admin/forum-post-report/" + reportId + "/status", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.head.querySelector("[name='csrf-token']").content,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ status: select.value })
        }).then(() => {
            new Noty({ type: "success", text: "Trạng thái đã cập nhật!" }).show();
        });
    }
</script>
