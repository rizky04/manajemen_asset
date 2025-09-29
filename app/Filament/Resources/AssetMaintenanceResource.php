<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetMaintenanceResource\Pages;
use App\Filament\Resources\AssetMaintenanceResource\RelationManagers;
use App\Models\AssetMaintenance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetMaintenanceResource extends Resource
{
    protected static ?string $model = AssetMaintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

        protected static ?string $navigationLabel = 'Maintance Asset';

        protected static ?string $navigationGroup = 'Asset Management';

        protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('vendor_id')
                    ->label('Vendor')
                    ->relationship('vendor', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\DatePicker::make('maintenance_date')
                    ->label('Tanggal Maintenance')
                    ->required(),

                Forms\Components\DatePicker::make('next_maintenance')
                    ->label('Tanggal Maintenance Berikutnya')
                    ->nullable(),

                Forms\Components\TextInput::make('cost')
                    ->label('Biaya')
                    ->numeric()
                    ->helperText('Masukkan angka saja, contoh: 100000')
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Status Maintenance')
                    ->options([
                        'scheduled' => 'Dijadwalkan',
                        'in_progress' => 'Sedang Perbaikan',
                        'done' => 'Selesai',
                    ])
                    ->default('scheduled')
                    ->required(),


                Forms\Components\Textarea::make('description')
                    ->label('Keterangan')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.name')->label('Asset'),
                Tables\Columns\TextColumn::make('vendor.name')->label('Vendor'),
                Tables\Columns\TextColumn::make('maintenance_date')->date()->label('Tanggal'),
                Tables\Columns\TextColumn::make('next_maintenance')->date()->label('Tanggal'),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Biaya')
                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-'),
                Tables\Columns\TextColumn::make('status_label')->label('Status')
                    ->colors([
                        'warning' => 'scheduled',
                        'info' => 'in_progress',
                        'success' => 'done',
                    ]),
                Tables\Columns\TextColumn::make('description')->label('Keterangan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('maintenance_date', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssetMaintenances::route('/'),
            'create' => Pages\CreateAssetMaintenance::route('/create'),
            'edit' => Pages\EditAssetMaintenance::route('/{record}/edit'),
        ];
    }
}
