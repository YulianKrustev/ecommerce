<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\OrderResource;
use Filament\Actions;

class EditOrder extends EditRedirect
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string
    {
        return 'Edit Order ' . $this->record->id;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
