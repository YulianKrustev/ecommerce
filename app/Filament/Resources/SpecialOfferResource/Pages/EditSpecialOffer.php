<?php

namespace App\Filament\Resources\SpecialOfferResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\SpecialOfferResource;
use App\Models\SpecialOffer;
use Filament\Actions;
use Filament\Notifications\Notification;

class EditSpecialOffer extends EditRedirect
{
    protected static string $resource = SpecialOfferResource::class;

    protected function afterSave(): void
    {
        $this->record->applySpecialOffer();
    }

    public function getTitle(): string
    {
        return 'Edit ' . $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, SpecialOffer $record) {
                    if ($record->is_active) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to delete!')
                            ->body('First deactivate offer.')
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
