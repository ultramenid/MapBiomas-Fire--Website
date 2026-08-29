<div class="overflow-x-auto rounded-md border border-line bg-surface">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-line bg-hover/50">
                {{ $head }}
            </tr>
        </thead>
        <tbody class="divide-y divide-line">
            {{ $slot }}
        </tbody>
    </table>
</div>
