<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColorResource\Pages;
use App\Filament\Resources\ColorResource\RelationManagers;
use App\Models\Color;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ColorResource extends Resource
{
    protected static ?string $model = Color::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                ColorPicker::make('hex_code')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                ColorColumn::make('hex_code')->label('Color'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->before(function (Tables\Actions\DeleteAction $action, Color $record) {
                            if ($record->products()->exists()) {
                                Notification::make()
                                    ->danger()
                                    ->title('Failed to delete!')
                                    ->body('This color is being used by products and cannot be deleted.')
                                    ->send();

                                $action->cancel();
                            }
                        }),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->before(function (DeleteBulkAction $action, $records) {
                        $shouldCancel = false;

                        foreach ($records as $record) {
                            if ($record->products()->exists()) {
                                Notification::make()
                                    ->danger()
                                    ->title("Failed to delete $record->name!")
                                    ->body('This color is being used by products and cannot be deleted.')
                                    ->send();

                                $shouldCancel = true;
                            }
                        }

                        if ($shouldCancel) {
                            $action->cancel();
                        }
                    }),
                ])->label('Delete')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListColors::route('/'),
            'create' => Pages\CreateColor::route('/create'),
            'edit' => Pages\EditColor::route('/{record}/edit'),
        ];
    }
}
