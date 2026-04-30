<?php
namespace App\Filament\Resources\Batches\Shared;

use App\Models\Batch;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BatchForm
{
    public static function configure(Schema $schema, string $type): Schema
    {
        return $schema->components([
            Section::make('Informasi Batch')
                ->columns(2)
                ->components([
                    TextInput::make('batch_code')
                        ->label('Kode Batch')
                        ->disabled()
                        ->dehydrated()
                        ->default(fn () => Batch::generateBatchCode($type))
                        ->columnSpanFull(),

                    TextInput::make('name')
                        ->label('Nama Batch')
                        ->required()
                        ->maxLength(200)
                        ->columnSpanFull(),

                    Select::make('competency_id')
                        ->label('Kompetensi')
                        ->relationship('competency', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpanFull(),

                    // Only show branch for Cabang type
                    Select::make('branch_id')
                        ->label('Cabang')
                        ->relationship('branch', 'name')
                        ->searchable()
                        ->preload()
                        ->required($type === 'Cabang')
                        ->hidden($type === 'HO')
                        ->columnSpanFull(),

                    Select::make('pic_id')
                        ->label('PIC / Trainer')
                        ->relationship('pic', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft'        => 'Draft',
                            'open'         => 'Open',
                            'berlangsung'  => 'Berlangsung',
                            'selesai'      => 'Selesai',
                            'dibatalkan'   => 'Dibatalkan',
                        ])
                        ->default('draft')
                        ->required(),

                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->required(),

                    DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->required()
                        ->afterOrEqual('start_date'),

                    TextInput::make('target_participants')
                        ->label('Target Peserta')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ]),
        ]);
    }
}