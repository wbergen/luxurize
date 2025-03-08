<?php

namespace App\Filament\Resources\ProviderOrderResource\Pages;

use App\Filament\Resources\ProviderOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProviderOrders extends ListRecords
{
    protected static string $resource = ProviderOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
