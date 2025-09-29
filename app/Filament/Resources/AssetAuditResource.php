<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetAuditResource\Pages;
use App\Filament\Resources\AssetAuditResource\RelationManagers;
use App\Models\AssetAudit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetAuditResource extends Resource
{
    protected static ?string $model = AssetAudit::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Asset Management';
    protected static ?string $navigationLabel = 'Audit Asset';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('audit_date')
                    ->label('Tanggal Audit')
                    ->required(),
                Forms\Components\Select::make('auditor_id')
                    ->label('Auditor')
                    ->relationship('auditor', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('condition')
                    ->label('Kondisi Asset')
                    ->options([
                        'good' => 'Baik',
                        'damaged' => 'Rusak',
                        'missing' => 'Hilang',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('remarks')
                    ->label('Keterangan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.name')->label('Asset'),
                Tables\Columns\TextColumn::make('audit_date')->date()->label('Tanggal Audit'),
                Tables\Columns\TextColumn::make('auditor.name')->label('Auditor'),
                Tables\Columns\BadgeColumn::make('condition_label')
                    ->label('Kondisi')
                    ->colors([
                        'success' => 'good',
                        'warning' => 'damaged',
                        'danger' => 'missing',
                    ]),
                Tables\Columns\TextColumn::make('remarks')->label('Keterangan'),
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
            'index' => Pages\ListAssetAudits::route('/'),
            'create' => Pages\CreateAssetAudit::route('/create'),
            'edit' => Pages\EditAssetAudit::route('/{record}/edit'),
        ];
    }
}
