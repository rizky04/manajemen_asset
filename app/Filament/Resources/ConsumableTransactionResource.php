<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumableTransactionResource\Pages;
use App\Filament\Resources\ConsumableTransactionResource\RelationManagers;
use App\Models\ConsumableTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsumableTransactionResource extends Resource
{
    protected static ?string $model = ConsumableTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventory Management';

    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Select::make('item_id')
    ->label('Barang Habis Pakai')
    ->relationship('item', 'name')
    ->searchable()
    ->preload()
    ->required(),
    Forms\Components\Select::make('type')
    ->label('Jenis Transaksi')
    ->options([
        'in' => 'Barang Masuk',
        'out' => 'Barang Keluar',
        'adjust' => 'Penyesuaian Stok',
    ])
    ->required(),

                Forms\Components\Select::make('employee_id')
    ->label('Karyawan / Penanggung Jawab')
    ->relationship('employee', 'name')
    ->searchable()
    ->preload()
    ->nullable(),

    Forms\Components\Select::make('department_id')
    ->label('Departemen')
    ->relationship('department', 'name')
    ->searchable()
    ->preload()
    ->nullable(),
                Forms\Components\DatePicker::make('date')
    ->label('Tanggal Transaksi')
    ->required()
    ->default(now()),
                Forms\Components\TextInput::make('qty')
    ->label('Jumlah')
    ->numeric()
    ->required(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')->label('Barang')
                ->sortable(),
               Tables\Columns\TextColumn::make('employee.name')->label('Karyawan')
                    ->sortable(),
Tables\Columns\TextColumn::make('department.name')->label('Departemen')->sortable(),
                Tables\Columns\TextColumn::make('date')->date()->label('Tanggal')
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty')->label('Jumlah')
                    ->sortable(),
              Tables\Columns\BadgeColumn::make('type')
                ->label('Jenis Transaksi')
                ->getStateUsing(fn ($record) => $record->type_label) // ✅ pakai accessor
                ->colors([
                    'success' => 'in',
                    'danger'  => 'out',
                    'warning' => 'adjust',
                ])
                ->sortable(),
                 Tables\Columns\TextColumn::make('note')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListConsumableTransactions::route('/'),
            'create' => Pages\CreateConsumableTransaction::route('/create'),
            'edit' => Pages\EditConsumableTransaction::route('/{record}/edit'),
        ];
    }
}
