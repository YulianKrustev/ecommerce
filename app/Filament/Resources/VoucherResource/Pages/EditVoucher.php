<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\VoucherResource;
use App\Models\Voucher;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;

class EditVoucher extends EditRedirect
{
    protected static string $resource = VoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->before(function (DeleteAction $action, Voucher $record) {

                if ($record->is_active) {
                    Notification::make()
                        ->danger()
                        ->title('Failed to delete!')
                        ->body('This voucher is active!')
                        ->send();

                    $action->cancel(); // Cancel the deletion
                }
            }),
        ];
    }
}
