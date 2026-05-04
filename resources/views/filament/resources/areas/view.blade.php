<x-filament-panels::page>
    {{ $this->content }}

    <div style="margin-top: 2rem;">
        @include('filament.pages.master-data.area-detail', $this->getViewData())
    </div>
</x-filament-panels::page>
