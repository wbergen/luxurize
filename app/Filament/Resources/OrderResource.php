<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderProductsResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderShippingResource\RelationManagers\ShippingRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')->disabled()->label('Order Id')->prefix('#'),
                Forms\Components\Select::make('order_status_id')
                    ->relationship(name: 'orderStatus', titleAttribute: 'label')
                    ->native(false)
                ,
                Forms\Components\Select::make('order_status_id')
                    ->relationship(name: 'orderStatus', titleAttribute: 'label')
                ->native(false)
                ,
                Forms\Components\TextInput::make('price')->disabled()->label('Total Price')->prefix('$')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('orderStatus.label')->label('Status'),
                Tables\Columns\TextColumn::make('providerOrder.provider_id')->label('Prov.ID'),
                Tables\Columns\TextColumn::make('providerOrder.provider_status')->label('Prov.Status'),
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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            ShippingRelationManager::class,
            ProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
