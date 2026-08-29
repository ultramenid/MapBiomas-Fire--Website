<x-cms-layout title="ATBD">
    <livewire:page-atbd />
</x-cms-layout>

{{-- Editor TinyMCE dibungkus wire:ignore, jadi Livewire tidak boleh
     menyentuh isinya. Saat kategori berganti, kontennya didorong ke editor
     yang sudah berdiri — jauh lebih ringan daripada membangunnya ulang. --}}
<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('atbd-konten-diganti', function (payload) {
            var data = Array.isArray(payload) ? payload[0] : payload;
            if (! data || typeof tinymce === 'undefined') {
                return;
            }

            ['contentEN', 'contentID'].forEach(function (nama) {
                var editor = tinymce.get(nama);
                if (editor) {
                    editor.setContent(data[nama] || '');
                }
            });
        });
    });
</script>
