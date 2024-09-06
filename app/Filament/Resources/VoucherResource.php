<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use function Laravel\Prompts\text;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Voucher Information')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->columnSpan(1),

                            TextInput::make('code')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->label('Voucher Code')
                                ->minLength(8)
                                ->maxLength(12),

                            TextInput::make('discount_amount')
                                ->label('Discount Amount')
                                ->numeric()
                                ->prefix('EUR')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if (!empty($state)) {
                                        $set('discount_percentage', null);
                                    }
                                }),

                            TextInput::make('discount_percentage')
                                ->label('Discount Percentage')
                                ->numeric()
                                ->maxValue(99)
                                ->suffix('%')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if (!empty($state)) {
                                        $set('discount_amount', null);
                                    }
                                }),

                            TextInput::make('min_order_value')
                                ->numeric()
                                ->minValue(50)
                                ->default(50)
                                ->prefix('EUR')
                                ->columnStart(1),

                            DateTimePicker::make('expires_at')
                                ->label('Expiration Date')
                                ->nullable()
                                ->columnStart(1),

                            TextInput::make('usage_limit')
                                ->numeric()
                                ->label('Usage Limit')
                                ->nullable()
                                ->columnStart(1),

                            Toggle::make('single_use')
                                ->label('Single-use per user')
                                ->default(false)
                                ->columnStart(1),

                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true)
                                ->columnStart(1),
                        ]),
                    ]),


                ])->columnSpan(2)
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('min_order_value')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('usage_limit')
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('usage_count')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                IconColumn::make('single_use')
                    ->alignCenter()
                    ->label('Single-use per user')
                    ->boolean()
                    ->alignCenter(),

                SelectColumn::make('is_active')
                    ->label('Active')
                    ->selectablePlaceholder(false)
                    ->options([
                        '0' => 'Inactive',
                        '1' => 'Active',
                    ])
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    DeleteAction::make()
                        ->before(function (DeleteAction $action, Voucher $record) {

                            if ($record->is_active) {
                                Notification::make()
                                    ->danger()
                                    ->title('Failed to delete!')
                                    ->body('This voucher is active!')
                                    ->send();

                                $action->cancel(); // Cancel the deletion
                            }
                        }),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->before(function (DeleteBulkAction $action, $records) {
                        $shouldCancel = false;

                        foreach ($records as $record) {
                            if ($record->is_active) {
                                Notification::make()
                                    ->danger()
                                    ->title("Failed to delete $record->name!")
                                    ->body('This voucher is active!')
                                    ->send();

                                $shouldCancel = true;
                            }
                        }

                        if ($shouldCancel) {
                            $action->cancel();
                        }
                    }),
                ])->label('Delete'),
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Discounts & Deals';
    }
}
