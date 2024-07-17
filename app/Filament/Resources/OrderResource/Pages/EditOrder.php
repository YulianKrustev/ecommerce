<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\OrderResource;
use Filament\Actions;

class EditOrder extends EditRedirect
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
