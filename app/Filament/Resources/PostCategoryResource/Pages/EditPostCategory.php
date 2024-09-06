<?php

namespace App\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\EditRedirect;
use App\Filament\Resources\PostCategoryResource;
use App\Models\Post;
use App\Models\PostCategory;
use Filament\Actions;
use Filament\Notifications\Notification;

class EditPostCategory extends EditRedirect
{
    protected static string $resource = PostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, PostCategory $record) {

                    if ($record->posts()->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to delete!')
                            ->body('This category has associated posts and cannot be deleted.')
                            ->send();

                        $action->cancel(); // Cancel the deletion
                    }
                }),
        ];
    }
}
