<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Products\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('product_type_id')
                    ->relationship(name: 'productType', titleAttribute: 'label')
                    ->native(false),
                Forms\Components\RichEditor::make('description')
                    ->fileAttachmentsDisk(config('filesystems.default'))
                    ->fileAttachmentsDirectory('article')
                    ->fileAttachmentsVisibility('public')
                    ->required(),
                Forms\Components\TextInput::make('price')->numeric()->required(),
                Forms\Components\FileUpload::make('image')
                    ->disk(config('filesystems.default'))
                    ->directory('products')
                    ->visibility('public')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->required(),
                Forms\Components\Select::make('tags')
                    ->placeholder('Select one or more Tags')
                    ->relationship(name: 'tags', titleAttribute: 'name')
                        ->multiple()
                        ->searchable()
                        ->native(false),
                Forms\Components\Select::make('categories')
                    ->placeholder('Select one or more Categories')
                    ->relationship(name: 'categories', titleAttribute: 'label')
                    ->multiple()
                    ->searchable()
                    ->native(false),
                Forms\Components\Toggle::make('for_sale')
                    ->label('For Sale?')
                    ->default(false),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('productType.label'),
                Tables\Columns\IconColumn::make('for_sale')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
