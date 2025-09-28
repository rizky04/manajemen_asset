<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetDisposalResource\Pages;
use App\Filament\Resources\AssetDisposalResource\RelationManagers;
use App\Models\AssetDisposal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetDisposalResource extends Resource
{
    protected static ?string $model = AssetDisposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';

    protected static ?string $navigationGroup = 'Asset Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asset_id')
                    ->label('Asset')
                    // ->relationship('asset', 'name', fn(Builder $query) => $query->where('status', 'active'))
                    ->relationship('asset', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('disposal_date')
                    ->label('Tanggal Disposisi')
                    ->required(),
                Forms\Components\Select::make('method')
                    ->label('Metode Disposisi')
                    ->options([
                        'sold' => 'Dijual',
                        'scrapped' => 'Dibuang',
                        'donated' => 'Disumbangkan',
                    ])->required(),
                Forms\Components\TextInput::make('value')
                    ->label('Nilai Sisa / Jual')
                    ->numeric()
                    ->helperText('Masukkan angka saja, contoh: 500000')
                    ->nullable(),
                Forms\Components\Textarea::make('reason')
                    ->label('Alasan Disposisi')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.name')->label('Asset'),
                Tables\Columns\TextColumn::make('disposal_date')->date()->label('Tanggal Disposisi'),
               Tables\Columns\TextColumn::make('method_label')->label('Metode'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai Sisa')
                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-'),
                Tables\Columns\TextColumn::make('reason')->label('Alasan'),
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
            'index' => Pages\ListAssetDisposals::route('/'),
            'create' => Pages\CreateAssetDisposal::route('/create'),
            'edit' => Pages\EditAssetDisposal::route('/{record}/edit'),
        ];
    }
}
