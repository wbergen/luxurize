<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObligationResource\Pages;
use App\Filament\Resources\ObligationResource\RelationManagers;
use App\Models\Obligation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObligationResource extends Resource
{
    protected static ?string $model = Obligation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')->disabled()->label('Obligation Id')->prefix('#'),
//                Forms\Components\Select::make('order_status_id')
//                    ->relationship(name: 'orderStatus', titleAttribute: 'label')
//                    ->native(false)
//                ,
//                Forms\Components\Select::make('order_status_id')
//                    ->relationship(name: 'orderStatus', titleAttribute: 'label')
//                ->native(false)
//                ,
                Forms\Components\TextInput::make('order.price')->disabled()->label('Total Price')->prefix('$')            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('order.price')->label('Price')->prefix('$')->color('success'),
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('obligationStatus.label')->label('Status'),
                Tables\Columns\TextColumn::make('order.paymentRecord.providerRecord.provider_id')->label('Prov.Payment Id'),
//                Tables\Columns\TextColumn::make('providerOrder.provider_id')->label('Prov.ID'),
//                Tables\Columns\TextColumn::make('providerOrder.provider_status')->label('Prov.Status'),
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
            'index' => Pages\ListObligations::route('/'),
            'create' => Pages\CreateObligation::route('/create'),
            'edit' => Pages\EditObligation::route('/{record}/edit'),
        ];
    }
}
