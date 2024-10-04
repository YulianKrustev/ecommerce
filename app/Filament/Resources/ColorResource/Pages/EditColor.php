<?php

namespace App\Filament\Resources\ColorResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\ColorResource;
use App\Models\Color;
use Filament\Actions;
use Filament\Notifications\Notification;

class EditColor extends EditRedirect
{
    protected static string $resource = ColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, Color $record) {
                    if ($record->products()->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to delete!')
                            ->body('This color is being used by products and cannot be deleted.')
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
