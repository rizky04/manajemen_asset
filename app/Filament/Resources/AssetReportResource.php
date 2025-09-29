<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetReportResource\Pages;
use App\Filament\Resources\AssetReportResource\RelationManagers;
use App\Models\AssetReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AssetReportResource extends Resource
{
    protected static ?string $model = \App\Models\Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Aset';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('asset_code')
                ->label('Kode Aset')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Nama Aset')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Kategori'),

            Tables\Columns\TextColumn::make('location.name')
                ->label('Lokasi'),

            Tables\Columns\TextColumn::make('purchase_date')
                ->label('Tanggal Perolehan')
                ->date(),

            Tables\Columns\TextColumn::make('purchase_price')
                ->label('Harga Perolehan')
                ->money('IDR'),

            Tables\Columns\BadgeColumn::make('condition')
                ->label('Kondisi')
                ->colors([
                    'success' => 'Baik',
                    'warning' => 'Perlu Perbaikan',
                    'danger' => 'Rusak',
                ]),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('category_id')
                ->label('Kategori')
                ->relationship('category', 'name'),

            Tables\Filters\Filter::make('purchase_date')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('from')->label('Dari'),
                    \Filament\Forms\Components\DatePicker::make('until')->label('Sampai'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['from'], fn ($q) => $q->whereDate('purchase_date', '>=', $data['from']))
                        ->when($data['until'], fn ($q) => $q->whereDate('purchase_date', '<=', $data['until']));
                }),
        ])
        ->headerActions([
            ExportBulkAction::make()->label('Export Excel'),
        ])
        ->bulkActions([
            ExportBulkAction::make()->label('Export Terpilih'),
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
            'index' => Pages\ListAssetReports::route('/'),
            'create' => Pages\CreateAssetReport::route('/create'),
            'edit' => Pages\EditAssetReport::route('/{record}/edit'),
        ];
    }
}
