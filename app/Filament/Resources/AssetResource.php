<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

     public static function getHeaderActions(): array
    {
        return [
            Action::make('print_qr')
                ->label('Print QR Semua')
                ->icon('heroicon-o-printer')
                ->url(route('assets.qr.print'))
                ->openUrlInNewTab(),
        ];
    }

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

        protected static ?string $navigationGroup = 'Asset Management';

        protected static ?int $navigationSort = 1;


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
                // Forms\Components\TextInput::make('asset_code')
                //     ->required()
                //     ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Select::make('vendor_id')
                    ->label('Vendor')
                    ->relationship('vendor', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Select::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('employee_id')
                    ->label('Pemilik / Penanggung Jawab')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\DatePicker::make('purchase_date')
                    ->label('Tanggal Pembelian')
                    ->nullable(),

                Forms\Components\TextInput::make('purchase_price')
                    ->label('Harga Pembelian')
                    ->numeric()
                    ->helperText('Masukkan angka saja, misal: 1000000')
                    ->nullable(),

                Forms\Components\TextInput::make('depreciation_val')
                    ->label('Nilai Penyusutan')
                    ->numeric()
                    ->helperText('Masukkan angka saja, misal: 100000')
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'borrowed' => 'Dipinjam',
                        'disposed' => 'Dihapus',
                        'repair' => 'Perbaikan',
                    ])
                    ->default('active')
                    ->required(),
                // readonly
                Forms\Components\TextInput::make('serial_number')
                    ->maxLength(255),
                Forms\Components\RichEditor::make('notes')
                    ->columnSpan('full')
                    ->nullable(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->columnSpan('full')
                    ->nullable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departemen')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Pemilik')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('depreciation_val')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_label')->label('Status'),
                Tables\Columns\TextColumn::make('serial_number')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
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
                Tables\Actions\ViewAction::make(),
            Action::make('print_qr')
                ->label('QR Code')
                ->icon('heroicon-o-qr-code')
                ->url(fn ($record) => route('assets.public.show', $record), true) // link tujuan
                ->openUrlInNewTab()
                ->extraAttributes(['class' => 'text-primary']),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('print_qr')
                ->label('Print QR')
                ->icon('heroicon-o-qr-code')
                ->action(function (Collection $records, $livewire) {
                    // ambil id yang dipilih
                    $ids = $records->pluck('id')->toArray();

                    // redirect ke route print
                    return redirect()->route('assets.qr.bulk', [
                        'ids' => implode(',', $ids),
                    ]);
                }),
            ])->headerActions([
            Action::make('print_qr_all')
                ->label('Print Semua QR')
                ->icon('heroicon-o-printer')
                ->url(route('assets.qr.all'))
                ->openUrlInNewTab(),
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
