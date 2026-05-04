<x-filament-panels::page>
    {{ $this->content }}

    {{-- Analytics Section --}}
    <div style="margin-top: 2rem;">
        @include('filament.resources.branches.analytics', $this->getViewData())
    </div>
</x-filament-panels::page>
