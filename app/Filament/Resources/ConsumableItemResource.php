<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumableItemResource\Pages;
use App\Filament\Resources\ConsumableItemResource\RelationManagers;
use App\Models\ConsumableItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsumableItemResource extends Resource
{
    protected static ?string $model = ConsumableItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

 protected static ?string $navigationGroup = 'Inventory Management';

 protected static ?string $navigationLabel = 'Item Habis Pakai';

 protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->label('Perusahaan')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                // Forms\Components\TextInput::make('item_code')
                //     ->label('Kode Barang')
                //     ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Nama Barang')
                    ->required(),

                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\TextInput::make('stock_qty')
                    ->label('Jumlah Stok')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\TextInput::make('unit')
                    ->label('Satuan')
                    ->required(),

                Forms\Components\Select::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_code')->label('Kode Barang'),
Tables\Columns\TextColumn::make('name')->label('Nama Barang'),
Tables\Columns\TextColumn::make('category.name')->label('Kategori'),
Tables\Columns\TextColumn::make('company.name')->label('Perusahaan'),
Tables\Columns\TextColumn::make('location.name')->label('Lokasi'),
Tables\Columns\TextColumn::make('stock_qty')->label('Stok'),
Tables\Columns\BadgeColumn::make('stock_status')
    ->label('Status Stok')
    ->colors([
        'success' => 'Tersedia',
        'danger' => 'Habis',
    ]),
Tables\Columns\TextColumn::make('unit')->label('Satuan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
            'index' => Pages\ListConsumableItems::route('/'),
            'create' => Pages\CreateConsumableItem::route('/create'),
            'edit' => Pages\EditConsumableItem::route('/{record}/edit'),
        ];
    }
}
