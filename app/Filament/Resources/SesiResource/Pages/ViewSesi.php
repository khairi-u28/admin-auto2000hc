<?php

namespace App\Filament\Resources\SesiResource\Pages;

use App\Filament\Resources\SesiResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSesi extends ViewRecord
{
    protected static string $resource = SesiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('lihat_laporan_batch')
                ->label('Lihat Laporan Batch')
                ->icon('heroicon-o-document-chart-bar')
                ->color('info')
                ->url(fn (): string => static::getResource()::getUrl('view', ['record' => $this->getRecord()])),
        ];
    }
}
