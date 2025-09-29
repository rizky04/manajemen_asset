<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetTransactionResource\Pages;
use App\Filament\Resources\AssetTransactionResource\RelationManagers;
use App\Models\AssetTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetTransactionResource extends Resource
{
    protected static ?string $model = AssetTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationLabel = 'Transaksi Asset';
    protected static ?string $navigationGroup = 'Asset Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('transaction_type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'borrow' => 'Pinjam',
                        'return' => 'Kembali',
                        'transfer' => 'Transfer',
                    ])
                    ->required(),
                Forms\Components\Select::make('from_company_id')
                    ->label('Dari Perusahaan')
                    ->relationship('fromCompany', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('to_company_id')
                    ->label('Ke Perusahaan')
                    ->relationship('toCompany', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('from_employee_id')
                    ->label('Dari Pegawai')
                    ->relationship('fromEmployee', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('to_employee_id')
                    ->label('Ke Pegawai')
                    ->relationship('toEmployee', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('from_location_id')
                    ->label('Dari Lokasi')
                    ->relationship('fromLocation', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('to_location_id')
                    ->label('Ke Lokasi')
                    ->relationship('toLocation', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal Transaksi')
                    ->required(),

                Forms\Components\RichEditor::make('note')
                    ->label('Keterangan')
                    ->columnSpan('full')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.name')->label('Asset'),
                Tables\Columns\TextColumn::make('status_label')->label('Status'),
                Tables\Columns\TextColumn::make('fromCompany.name')->label('Dari Perusahaan'),
                Tables\Columns\TextColumn::make('toCompany.name')->label('Ke Perusahaan'),
                Tables\Columns\TextColumn::make('fromEmployee.name')->label('Dari Pegawai'),
                Tables\Columns\TextColumn::make('toEmployee.name')->label('Ke Pegawai'),
                Tables\Columns\TextColumn::make('fromLocation.name')->label('Dari Lokasi'),
                Tables\Columns\TextColumn::make('toLocation.name')->label('Ke Lokasi'),
                Tables\Columns\TextColumn::make('date')->date()->label('Tanggal'),
                Tables\Columns\TextColumn::make('note')->label('Keterangan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('date', 'desc')
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
            'index' => Pages\ListAssetTransactions::route('/'),
            'create' => Pages\CreateAssetTransaction::route('/create'),
            'edit' => Pages\EditAssetTransaction::route('/{record}/edit'),
        ];
    }
}
