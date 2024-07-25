<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRedirect
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string
    {
        return 'Edit ' . $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
