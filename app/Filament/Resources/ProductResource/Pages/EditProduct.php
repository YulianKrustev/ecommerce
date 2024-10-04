<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Notifications\Notification;
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
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, Product $record) {
                    if ($record->orderItems()->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to delete!')
                            ->body('This product is used in orders and cannot be deleted.')
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
