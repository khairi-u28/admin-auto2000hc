<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Area;
use App\Models\Branch;
use App\Models\Region;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Utama')
                ->columns(2)
                ->components([
                    TextInput::make('nrp')
                        ->label('NRP')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),
                    TextInput::make('nama_lengkap')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(150)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('full_name', $state))
                        ->live(onBlur: true),
                    Hidden::make('full_name'),
                    TextInput::make('position_name')
                        ->label('Nama Posisi')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('pos')
                        ->label('POS')
                        ->maxLength(255),
                    Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'active'   => 'aktif',
                            'inactive' => 'non_aktif',
                        ])
                        ->default('active'),
                    Select::make('job_role_id')
                        ->label('Jabatan')
                        ->relationship('jobRole', 'name')
                        ->searchable()
                        ->preload(),
                ]),
            Section::make('Penempatan')
                ->columns(2)
                ->components([
                    Select::make('region_id')
                        ->label('Region')
                        ->options(fn () => Region::orderBy('nama_region')->pluck('nama_region', 'id'))
                        ->searchable()
                        ->live()
                        ->dehydrated(false)
                        ->afterStateUpdated(function (Set $set): void {
                            $set('area_id', null);
                            $set('branch_id', null);
                            $set('region', null);
                            $set('area', null);
                        }),
                    Select::make('area_id')
                        ->label('Area')
                        ->options(fn (Get $get) => Area::query()
                            ->when($get('region_id'), fn ($query, $regionId) => $query->where('region_id', $regionId))
                            ->orderBy('nama_area')
                            ->pluck('nama_area', 'id'))
                        ->searchable()
                        ->live()
                        ->dehydrated(false)
                        ->afterStateUpdated(function (Set $set): void {
                            $set('branch_id', null);
                            $set('area', null);
                        }),
                    Select::make('branch_id')
                        ->label('Cabang')
                        ->options(fn (Get $get) => Branch::query()
                            ->when($get('area_id'), fn ($query, $areaId) => $query->where('area_id', $areaId))
                            ->orderByRaw('COALESCE(nama, name) asc')
                            ->get()
                            ->mapWithKeys(fn (Branch $branch) => [$branch->id => $branch->nama ?? $branch->name]))
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateHydrated(function (Set $set, ?string $state): void {
                            $branch = $state ? Branch::with(['areaRelation.region', 'regionRelation'])->find($state) : null;

                            if (! $branch) {
                                return;
                            }

                            $set('region_id', $branch->region_id);
                            $set('area_id', $branch->area_id);
                            $set('area', $branch->areaRelation?->nama_area ?? $branch->area);
                            $set('region', $branch->regionRelation?->nama_region ?? $branch->region);
                        })
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                            $branch = $state ? Branch::with(['areaRelation.region', 'regionRelation'])->find($state) : null;

                            $set('area', $branch?->areaRelation?->nama_area ?? $branch?->area);
                            $set('region', $branch?->regionRelation?->nama_region ?? $branch?->region);
                            $set('region_id', $branch?->region_id ?? $get('region_id'));
                            $set('area_id', $branch?->area_id ?? $get('area_id'));
                        }),
                    Hidden::make('area'),
                    Hidden::make('region'),
                ]),
            Section::make('Data Pribadi & Rekam HAV')
                ->columns(2)
                ->components([
                    DatePicker::make('entry_date')
                        ->label('Tanggal Masuk')
                        ->required(),
                    DatePicker::make('date_of_birth')
                        ->label('Tanggal Lahir'),
                    TextInput::make('masa_bakti')
                        ->label('Masa Bakti')
                        ->maxLength(255),
                    TextInput::make('hav_score')
                        ->label('HAV Score')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(11),
                    TextInput::make('hav_category')
                        ->label('Kategori HAV')
                        ->maxLength(50),
                    TextInput::make('grade')
                        ->label('Grade')
                        ->maxLength(10),
                    TextInput::make('italent_user')
                        ->label('iTalent User')
                        ->maxLength(100),
                ]),
        ]);
    }
}
