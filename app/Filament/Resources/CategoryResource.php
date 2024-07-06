<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use voku\helper\ASCII;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

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
                                Forms\Components\TextInput::make('name')
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
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->columnSpanFull()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set) {
                                        if ($operation == 'edit' || $state == null) {
                                            return;
                                        }
                                        $truncatedState = mb_substr($state, 0, 155);
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
                            Forms\Components\TagsInput::make('meta_keywords')
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->sortable()
                    ->label('Products'),
                TextColumn::make('slug')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
