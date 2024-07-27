<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialOfferResource\Pages;
use App\Filament\Resources\SpecialOfferResource\RelationManagers;
use App\Models\SpecialOffer;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
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
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use voku\helper\ASCII;

class SpecialOfferResource extends Resource
{
    protected static ?string $model = SpecialOffer::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Create New Offer')->tabs([
                    Tab::make('Create Offer')->schema([
                        Grid::make([
                            'sm' => 1,
                        ])
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(80)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set) {
                                        if ($operation == 'edit' || $state == null) {
                                            return;
                                        }
                                        $set('meta_title', ASCII::to_ascii($state) . " - " . config("app.name"));
                                        $set('slug', ASCII::to_ascii(Str::slug($state)));
                                        $set('image_alt', $state);
                                    }),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(60)
                                    ->unique(ignoreRecord: true)
                                    ->live(debounce: 500)
                                    ->hint(function ($state) {
                                        $maxCount = 50;
                                        $charactersCount = mb_strlen($state);
                                        $leftCharacters = $maxCount - $charactersCount;

                                        return 'Characters left: ' . $leftCharacters;

                                    }),

                                Select::make('products')
                                    ->relationship('products', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->label('Select Products')
                                    ->preload()
                                    ->reactive()
                                    ->placeholder('Choose either categories or products.')
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!empty($state)) {
                                            $set('categories', []);
                                        }
                                    }),

                                FileUpload::make('image')
                                    ->image()
//                                    ->optimize('webp')
                                    ->required()
                                    ->columnSpan(1)
                                    ->directory('special_offers')
                                    ->imageEditor(),

                                Select::make('categories')
                                    ->relationship('categories', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->placeholder('Choose either categories or products.')
                                    ->label('Select Categories')
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!empty($state)) {
                                            $set('products', []);
                                        }
                                    }),

                                TextInput::make('image_alt')
                                    ->label('Image Alt')
                                    ->required(),

                                TextInput::make('discount_amount')
                                    ->label('Discount Amount')
                                    ->placeholder('Choose either amount or percentage')
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
                                    ->placeholder('Choose either amount or percentage')
                                    ->numeric()
                                    ->suffix('%')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!empty($state)) {
                                            $set('discount_amount', null);
                                        }
                                    }),

                                RichEditor::make('description')
                                    ->required()
                                    ->columnSpanFull()
                                    ->live(debounce: 500)
                                    ->hint(function ($state) {
                                        $charactersCount = mb_strlen($state);

                                        return 'Characters: ' . $charactersCount;
                                    })
                                    ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set) {
                                        if ($operation == 'edit' || $state == null) {
                                            return;
                                        }
                                        $plainTextState = strip_tags($state);
                                        $truncatedState = mb_substr($plainTextState, 0, 155);
                                        $truncatedState = rtrim($truncatedState);
                                        $truncatedState .= '...';
                                        $set('meta_description', $truncatedState);
                                    }),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->required()
                                    ->default('true')
                            ])->columns(2)
                    ]),
                    Tab::make('SЕО')
                        ->schema([
                            TextInput::make('meta_title')->required()->maxLength(60)
                                ->required()
                                ->label('Meta Title')
                                ->maxLength(60)
                                ->live(debounce: 500)
                                ->hint(function ($state) {
                                    $maxCount = 60;
                                    $charactersCount = mb_strlen($state);
                                    $leftCharacters = $maxCount - $charactersCount;

                                    return 'Characters left: ' . $leftCharacters;

                                }),

                            Textarea::make('meta_description')
                                ->required()
                                ->label('Meta Description')
                                ->maxLength(160)
                                ->live(debounce: 500)
                                ->hint(function ($state) {
                                    $maxCount = 160;
                                    $charactersCount = mb_strlen($state);
                                    $leftCharacters = $maxCount - $charactersCount;

                                    return 'Characters left: ' . $leftCharacters;

                                }),

                            TagsInput::make('meta_keywords')
                                ->label('Keywords')
                                ->placeholder('Add New Keyword')
                        ])
                ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('discount_amount')
                    ->label('Discount Amount')
                    ->money('EUR')
                    ->sortable()
                    ->default(0.00),

                TextColumn::make('discount_percentage')
                    ->label('Discount Percentage')
                    ->suffix('%')
                    ->default(0)
                    ->sortable(),

                SelectColumn::make('is_active')
                    ->label('Active')
                    ->selectablePlaceholder(false)
                    ->options([
                        '0' => 'Inactive',
                        '1' => 'Active',
                    ])
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->before(function (Tables\Actions\DeleteAction $action, SpecialOffer $record) {
                            if ($record->is_active) {
                                Notification::make()
                                    ->danger()
                                    ->title('Failed to delete!')
                                    ->body('First deactivate offer.')
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
                            if ($record->is_active) {
                                Notification::make()
                                    ->danger()
                                    ->title("Failed to delete $record->name!")
                                    ->body('First deactivate offer.')
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
            'index' => Pages\ListSpecialOffers::route('/'),
            'create' => Pages\CreateSpecialOffer::route('/create'),
            'edit' => Pages\EditSpecialOffer::route('/{record}/edit'),
        ];
    }
}
