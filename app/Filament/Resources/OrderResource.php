<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
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
                Forms\Components\Select::make('user_id')
                        ->searchable(['name', 'email'])
                        ->disabled()
                        ->relationship(name: 'user', titleAttribute: 'email')
                    ->native(false)
                ,
                Forms\Components\Select::make('order_status_is')
                    ->relationship(name: 'orderStatus', titleAttribute: 'label')
                ->native(false)
                ,
                Forms\Components\TextInput::make('price')->disabled()->label('Total Price')->prefix('$'),
                Forms\Components\Select::make('products')
                    ->disabled()
                    ->multiple()
                    ->searchable()
                    ->relationship(name: 'products', titleAttribute: 'name')
                    ->native(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('orderStatus.label'),
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
            //
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
