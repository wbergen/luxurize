<?php

namespace App\Filament\Resources\ProviderOrderResource\Pages;

use App\Filament\Resources\ProviderOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProviderOrder extends EditRecord
{
    protected static string $resource = ProviderOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
