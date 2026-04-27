<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SesiResource\Pages;
use App\Filament\Resources\SesiResource\RelationManagers\ParticipantsRelationManager;
use App\Filament\Resources\SesiResource\RelationManagers\RatingsRelationManager;
use App\Filament\Resources\SesiResource\RelationManagers\TrainersRelationManager;
use App\Models\Sesi;
use App\Models\Curriculum;
use App\Models\Employee;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;

class SesiResource extends Resource
{
    protected static ?string $model = Sesi::class;

    protected static ?string $slug = 'batches';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Batch';

    protected static ?string $pluralModelLabel = 'Batch';

    public static function getNavigationGroup(): ?string
    {
        return 'Enrollment & Operasional';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Batch')->columns(2)->components([
                Select::make('curriculum_id')
                    ->label('Kurikulum')
                    ->options(fn () => Curriculum::query()->pluck('title', 'id'))
                    ->searchable()
                    ->preload(),
                Select::make('pic_employee_id')
                    ->label('PIC')
                    ->options(fn () => Employee::query()->orderBy('full_name')->pluck('full_name', 'id'))
                    ->searchable()
                    ->preload(),
                TextInput::make('kode_batch')
                    ->label('Kode Batch')
                    ->required()
                    ->maxLength(255),
                TextInput::make('title')
                    ->label('Nama Batch')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'aktif',
                        'non_aktif' => 'non_aktif',
                        'selesai' => 'selesai',
                        'dibatalkan' => 'dibatalkan',
                    ])
                    ->default('non_aktif')
                    ->required(),
                TextInput::make('capacity')
                    ->label('Kapasitas')
                    ->numeric(),
                DatePicker::make('start_date')
                    ->label('Active From'),
                DatePicker::make('end_date')
                    ->label('Active Until'),
                TextInput::make('location')
                    ->label('Lokasi')
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Info Batch')->columns(2)->components([
                TextEntry::make('kode_batch')->label('Kode Batch'),
                TextEntry::make('title')->label('Nama Batch'),
                TextEntry::make('curriculum.title')->label('Kurikulum')->placeholder('-'),
                TextEntry::make('pic.nama_lengkap')->label('PIC')->placeholder('-'),
                TextEntry::make('start_date')->label('Active From')->date('d/m/Y'),
                TextEntry::make('end_date')->label('Active Until')->date('d/m/Y'),
                TextEntry::make('status')->label('Status')->badge(),
                TextEntry::make('completion_rate')
                    ->label('Completion Rate')
                    ->state(fn (Sesi $record): string => $record->completion_rate . '%'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_batch')->label('Kode Batch')->searchable()->sortable(),
                TextColumn::make('title')->label('Nama Batch')->searchable()->sortable(),
                TextColumn::make('pic.nama_lengkap')->label('PIC')->placeholder('-')->searchable(),
                TextColumn::make('status')->label('Status')->badge()->sortable(),
                TextColumn::make('peserta_count')->label('Jml. Peserta')->counts('peserta')->sortable(),
                TextColumn::make('completion_rate')->label('Completion Rate')
                    ->state(fn (Sesi $record): string => $record->completion_rate . '%'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'aktif' => 'aktif',
                        'non_aktif' => 'non_aktif',
                        'selesai' => 'selesai',
                        'dibatalkan' => 'dibatalkan',
                    ]),
            ])
            ->defaultSort('start_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            ParticipantsRelationManager::class,
            TrainersRelationManager::class,
            RatingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSesis::route('/'),
            'create' => Pages\CreateSesi::route('/create'),
            'view' => Pages\ViewSesi::route('/{record}'),
            'edit' => Pages\EditSesi::route('/{record}/edit'),
        ];
    }
}
