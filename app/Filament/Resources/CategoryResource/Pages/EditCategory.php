<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use App\Models\SpecialOffer;
use Filament\Actions;
use Filament\Notifications\Notification;

class EditCategory extends EditRedirect
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, Category $record) {
                    if ($record->products()->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to delete!')
                            ->body('Category has products.')
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
