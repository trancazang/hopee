@php
    $isRich = $attributes->get('rich') === true || $attributes->get('rich') === 'true';
@endphp

<div {!! isset($xShow) && !empty($xShow) ? "x-show=\"{$xShow}\"" : "" !!} class="mb-4">
    @if (isset($label))
        <label for="{{ $id }}" class="block mb-2 font-medium text-gray-900 dark:text-slate-400">{{ $label }}</label>
    @endif

    @if ($isRich)
    <div wire:ignore>
    @endif
        <textarea
            id="{{ $id }}"
            class="block p-2.5 w-full min-h-36 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
            {{ $attributes }}
        >{{ old($id, $value ?? '') }}</textarea>
    @if ($isRich)
    </div>
    @endif

    @include ('forum::components.form.error')
</div>

@if ($isRich)
    @once
        <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    @endonce

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let editorInstance = null;
            let isInitialized = false;
            
            // Đợi Livewire load xong
            if (window.Livewire) {
                initCKEditor();
            } else {
                document.addEventListener("livewire:init", initCKEditor);
            }

            function initCKEditor() {
                const el = document.getElementById("{{ $id }}");
                if (el && !el.dataset.ckeditorLoaded && !isInitialized) {
                    console.log('Initializing CKEditor for:', "{{ $id }}");
                    isInitialized = true;
                    
                    ClassicEditor.create(el, {
                        toolbar: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'link',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'outdent',
                            'indent',
                            '|',
                            'uploadImage',
                            'blockQuote',
                            'insertTable',
                            'mediaEmbed',
                            'undo',
                            'redo'
                        ],
                        heading: {
                            options: [
                                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                            ]
                        },
                        image: {
                            toolbar: [
                                'imageTextAlternative',
                                'imageStyle:inline',
                                'imageStyle:block',
                                'imageStyle:side'
                            ]
                        }
                    })
                    .then(editor => {
                        el.dataset.ckeditorLoaded = true;
                        editorInstance = editor;
                        el._ckeditorInstance = editor; // Lưu reference vào DOM element
                        console.log('CKEditor loaded successfully');
                        
                        // Debounce function để tránh update liên tục
                        let debounceTimer;
                        
                        // Sync data với Livewire (debounced)
                        editor.model.document.on('change:data', () => {
                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(() => {
                                const modelPath = "{{ $attributes->wire('model')->value() ?? $attributes->wire('model.defer')->value() }}";
                                if (modelPath) {
                                    // Update giá trị textarea trước
                                    el.value = editor.getData();
                                    // Sau đó sync với Livewire
                                    window.Livewire.find('{{ $this->getId() }}').set(modelPath, editor.getData(), false);
                                }
                            }, 500);
                        });

                        // Set initial content
                        const initialContent = el.value;
                        if (initialContent) {
                            editor.setData(initialContent);
                        }
                        
                        // Setup observer ngay sau khi khởi tạo
                        setTimeout(() => {
                            setupTextareaObserver();
                        }, 100);
                    })
                    .catch(error => {
                        console.error('CKEditor error:', error);
                        isInitialized = false;
                    });
                }
            }
            
            // Lắng nghe event reset từ Livewire
            window.addEventListener('resetCKEditor', function() {
                if (editorInstance) {
                    editorInstance.setData('');
                    console.log('CKEditor reset via event');
                }
            });
            
            // Lắng nghe khi Livewire component được re-render
            document.addEventListener('livewire:load', function() {
                const el = document.getElementById("{{ $id }}");
                if (el && editorInstance && el.value === '') {
                    editorInstance.setData('');
                    console.log('CKEditor reset on livewire:load');
                }
            });
        });

        // MutationObserver để theo dõi thay đổi textarea
        function setupTextareaObserver() {
            const textarea = document.getElementById("{{ $id }}");
            if (textarea && textarea._ckeditorInstance) {
                // Lưu giá trị trước đó
                let previousValue = textarea.value;
                
                // Sử dụng MutationObserver để theo dõi thay đổi
                const observer = new MutationObserver(() => {
                    const currentValue = textarea.value;
                    const editorData = textarea._ckeditorInstance.getData();
                    
                    // Nếu textarea bị reset về rỗng và CKEditor vẫn có nội dung
                    if (currentValue === '' && editorData !== '') {
                        textarea._ckeditorInstance.setData('');
                        console.log('✅ CKEditor reset - textarea cleared');
                    }
                    // Nếu textarea có giá trị khác với CKEditor
                    else if (currentValue !== editorData) {
                        textarea._ckeditorInstance.setData(currentValue);
                        console.log('✅ CKEditor synced with textarea');
                    }
                    
                    previousValue = currentValue;
                });
                
                // Theo dõi thay đổi thuộc tính value
                observer.observe(textarea, {
                    attributes: true,
                    attributeFilter: ['value']
                });
                
                // Cũng theo dõi thay đổi trực tiếp trên value
                let checkInterval = setInterval(() => {
                    if (!document.getElementById("{{ $id }}")) {
                        clearInterval(checkInterval);
                        return;
                    }
                    
                    const currentValue = textarea.value;
                    if (currentValue !== previousValue) {
                        const editorData = textarea._ckeditorInstance.getData();
                        
                        if (currentValue === '' && editorData !== '') {
                            textarea._ckeditorInstance.setData('');
                            console.log('✅ CKEditor reset - interval check');
                        } else if (currentValue !== editorData) {
                            textarea._ckeditorInstance.setData(currentValue);
                            console.log('✅ CKEditor synced - interval check');
                        }
                        
                        previousValue = currentValue;
                    }
                }, 500);
            }
        }
        
        // Hook xử lý mọi thay đổi từ Livewire
        Livewire.hook('message.processed', (message, component) => {
            setTimeout(() => {
                setupTextareaObserver();
            }, 100);
        });
    </script>
@endif