<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProviderOrderResource\Pages;
use App\Filament\Resources\ProviderOrderResource\RelationManagers;
use App\Models\ProviderOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProviderOrderResource extends Resource
{
    protected static ?string $model = ProviderOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')->disabled(),
                Forms\Components\TextInput::make('provider_id')->disabled(),
                Forms\Components\TextInput::make('provider_status')->disabled(),
                Forms\Components\TextInput::make('provider_cut')->prefix('$')->disabled(),
                Forms\Components\TextInput::make('provider_gross')->prefix('$')->disabled(),
                Forms\Components\TextInput::make('provider_net')->prefix('$')->disabled(),
                Forms\Components\TextInput::make('provider_payment_id')->disabled(),
                Forms\Components\TextInput::make('payer_id')->disabled(),
                Forms\Components\TextInput::make('payer_email')->disabled(),
                Forms\Components\TextInput::make('payer_name')->disabled(),
                Forms\Components\TextInput::make('payer_last_name')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('created_at')->label('Time'),
                Tables\Columns\TextColumn::make('provider_id')->searchable()->label('Prov. ID'),
                Tables\Columns\TextColumn::make('provider_status')->label('Prov. Status'),
                Tables\Columns\TextColumn::make('provider_cut')->prefix('$')->label('Prov. Cut'),
                Tables\Columns\TextColumn::make('provider_gross')->prefix('$')->label('Gross'),
                Tables\Columns\TextColumn::make('provider_net')->prefix('$')->label('Net'),
                Tables\Columns\TextColumn::make('provider_payment_id')->searchable()->label('Prov. Payment ID'),
                Tables\Columns\TextColumn::make('payer_id')->label('Prov. Payer ID'),
                Tables\Columns\TextColumn::make('payer_email')->label('Prov.P  Email'),
                Tables\Columns\TextColumn::make('payer_name')->label('Prov.P Name'),
                Tables\Columns\TextColumn::make('payer_last_name')->label('Prov.P Last Name'),
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
            ])->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListProviderOrders::route('/'),
            'create' => Pages\CreateProviderOrder::route('/create'),
            'edit' => Pages\EditProviderOrder::route('/{record}/edit'),
        ];
    }
}
