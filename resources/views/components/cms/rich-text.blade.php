@props([
    'field', // Livewire property to sync with
    'label' => null,
    'height' => 420,
])

@php
// Id elemen = nama properti Livewire, supaya skrip luar (mis. halaman ATBD
// yang memanggil tinymce.get('contentEN')) tetap menemukan editornya.
$id = $field;
@endphp

<div>
    @if ($label)
        <span class="mb-1.5 block text-xs font-medium text-ink">{{ $label }}</span>
    @endif
    <div wire:ignore
         x-init="tinymce.init({
            selector: '#{{ $id }}',
            height: {{ $height }},
            promotion: false,
            branding: false,
            license_key: 'gpl',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            highlight_on_focus: false,
            // Warna UI editor dikendalikan resources/css/cms/tinymce.css via
            // var(--cms-*), jadi cukup skin oxide — dark mode mengikuti
            // kelas .dark pada <html> secara live tanpa re-init.
            skin: 'oxide',
            plugins: 'lists advlist autolink link image charmap anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons help',
            toolbar: 'undo redo | bold italic underline forecolor backcolor | link image | bullist numlist alignleft aligncenter alignright alignjustify outdent indent | removeformat | fullscreen help',
            menubar: 'file edit view insert format tools table help',
            file_picker_callback: function(callback, value, meta) {
                const x = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                const y = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                let cmsURL = '/cms/fire-filemanager?editor=' + meta.fieldname;
                cmsURL += (meta.filetype == 'image') ? '&type=Images' : '&type=Files';
                tinymce.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: 'yes',
                    close_previous: 'no',
                    onMessage: (api, message) => { callback(message.content); }
                });
            },
            setup: function(editor) {
                // Konten iframe ikut menerjemahkan kelas .dark pada <html> host.
                const syncContentTheme = function() {
                    const doc = editor.contentDocument;
                    if (doc && doc.documentElement) {
                        doc.documentElement.classList.toggle('dark',
                            document.documentElement.classList.contains('dark'));
                    }
                };
                editor.on('init', function() {
                    syncContentTheme();
                    editor.dom.addStyle(
                        'body{background-color:#ffffff;color:#171717}' +
                        'body a{color:#c9544a;text-decoration:underline}' +
                        '.dark body{background-color:#151515;color:#ededed}' +
                        '.dark body a{color:#f09689;text-decoration:underline}'
                    );
                });
                new MutationObserver(syncContentTheme)
                    .observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
                editor.on('change keyup', function(e) {
                    @this.set('{{ $field }}', editor.getContent());
                });
            }
        });">
        <textarea id="{{ $id }}" name="{{ $field }}" rows="12" style="visibility:hidden">{{ $slot }}</textarea>
    </div>
</div>
