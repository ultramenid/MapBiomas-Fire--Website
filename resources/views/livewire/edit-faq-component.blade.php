<div>
    <x-cms.page-header title="Edit FAQ" description="Update the question and its answer.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.faq.index') }}">Cancel</x-cms.button>
            <x-cms.button wire:click="storeAksi" loadingTarget="storeAksi">Save</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <x-cms.form-tabs class="max-w-3xl">
        <x-slot:en>
            <div class="space-y-4">
                <x-cms.panel title="Question">
                    <textarea rows="3" wire:model="questionEN" placeholder="Question in English…"
                              class="w-full rounded-md border border-line-strong bg-surface px-2.5 py-2 text-sm text-ink placeholder:text-ink-subtle cms-focus"></textarea>
                </x-cms.panel>
                <x-cms.panel title="Answer">
                    <x-cms.rich-text field="answerEN" :height="420">{{ $answerEN }}</x-cms.rich-text>
                </x-cms.panel>
            </div>
        </x-slot:en>
        <x-slot:idn>
            <div class="space-y-4">
                <x-cms.panel title="Pertanyaan">
                    <textarea rows="3" wire:model="questionID" placeholder="Pertanyaan dalam Bahasa Indonesia…"
                              class="w-full rounded-md border border-line-strong bg-surface px-2.5 py-2 text-sm text-ink placeholder:text-ink-subtle cms-focus"></textarea>
                </x-cms.panel>
                <x-cms.panel title="Jawaban">
                    <x-cms.rich-text field="answerID" :height="420">{{ $answerID }}</x-cms.rich-text>
                </x-cms.panel>
            </div>
        </x-slot:idn>
    </x-cms.form-tabs>

    <div class="mt-8 flex max-w-3xl items-center justify-end gap-2 border-t border-line pt-5">
        <x-cms.button variant="secondary" href="{{ route('cms.faq.index') }}">Cancel</x-cms.button>
        <x-cms.button wire:click="storeAksi" loadingTarget="storeAksi">Save</x-cms.button>
    </div>
</div>
