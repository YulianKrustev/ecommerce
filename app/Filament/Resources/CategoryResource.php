<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use voku\helper\ASCII;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-tag';

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
                Tabs::make('Create New Category')->tabs([
                    Tab::make('Create Category')->schema([
                        Grid::make([
                            'sm' => 1,
                        ])
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(60)
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

                                FileUpload::make('image')
                                    ->image()
//                                    ->optimize('webp')
                                    ->columnSpanFull()
                                    ->directory('categories')
                                    ->imageEditor(),

                                TextInput::make('image_alt'),

                                RichEditor::make('description')
                                    ->required()
                                    ->columnSpanFull()
                                    ->live(onBlur: true)
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
                                ->maxLength(160)
                                ->live(debounce: 500)
                                ->hint(function ($state) {
                                    $maxCount = 160;
                                    $charactersCount = mb_strlen($state);
                                    $leftCharacters = $maxCount - $charactersCount;

                                    return 'Characters left: ' . $leftCharacters;

                                }),

                            TagsInput::make('meta_keywords')
                                ->placeholder('New keyword')
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

                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ])
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->before(function (Tables\Actions\DeleteAction $action, Category $record) {
                            if ($record->products()->exists()) {
                                Notification::make()
                                    ->danger()
                                    ->title('Failed to delete!')
                                    ->body('Category has products.')
                                    ->send();

                                $action->cancel();
                            }
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
