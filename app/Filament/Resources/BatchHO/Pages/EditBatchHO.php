<?php
namespace App\Filament\Resources\BatchHO\Pages;

use App\Filament\Resources\BatchHO\BatchHOResource;
use App\Models\BatchParticipant;
use App\Models\Employee;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditBatchHO extends EditRecord
{
    protected static string $resource = BatchHOResource::class;

    public function fillForm(): void
    {
        parent::fillForm();

        $this->data['evaluation'] = $this->record->evaluation ?? null;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return array_merge($data, [
            'evaluation' => $this->data['evaluation'] ?? $this->record->evaluation,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->color('primary')
                ->icon('heroicon-o-check')
                ->action('save'),
            Action::make('cancel')
                ->label('Batal')
                ->color('secondary')
                ->icon('heroicon-o-x')
                ->url($this->getResource()::getUrl('index')),
            DeleteAction::make(),
            // "Kirim Undangan Batch" toolbar action
            Action::make('send_invitations')
                ->label('Kirim Undangan Batch')
                ->color('info')
                ->icon('heroicon-o-envelope')
                ->visible(fn () => 
                    $this->record->status === 'pendaftaran' &&
                    $this->record->participants()
                        ->where('status', 'menunggu_undangan')
                        ->exists()
                )
                ->requiresConfirmation()
                ->modalHeading('Kirim Undangan ke Semua Peserta?')
                ->modalDescription('Semua peserta dengan status "Menunggu Undangan" akan diubah menjadi "Diundang". Mekanisme pengiriman email akan dikembangkan terpisah.')
                ->action(function () {
                    $count = $this->record->participants()
                        ->where('status', 'menunggu_undangan')
                        ->update([
                            'status' => 'diundang',
                            'invitation_sent_at' => now(),
                        ]);
                    Notification::make()
                        ->title("Undangan dikirim ke {$count} peserta")
                        ->success()
                        ->send();
                }),
        ];
    }

    // Override to provide custom tabbed view
    public function getView(): string
    {
        return 'filament.pages.batch.edit-batch';
    }
}