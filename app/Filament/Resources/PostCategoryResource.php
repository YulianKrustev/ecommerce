<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostCategoryResource\Pages;
use App\Filament\Resources\PostCategoryResource\RelationManagers;
use App\Models\PostCategory;
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
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use voku\helper\ASCII;

class PostCategoryResource extends Resource
{
    protected static ?string $model = PostCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

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
                                        $set('meta_title', ASCII::to_ascii($state) . " | " . config("app.name"));
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
                                    ->optimize('webp')
                                    ->columnSpanFull()
                                    ->directory('postCategories')
                                    ->imageEditor()
                                    ->imagePreviewHeight('350px'),

                                TextInput::make('image_alt')
                                    ->label('Image Alt'),

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
                                        $truncatedState = mb_substr($plainTextState, 0, 133);
                                        $lastSpacePosition = mb_strrpos($truncatedState, ' ');

                                        if ($lastSpacePosition !== false) {
                                            $truncatedState = mb_substr($truncatedState, 0, $lastSpacePosition);
                                        }
                                        $truncatedState = rtrim($truncatedState);
                                        $truncatedState .= '...FREE NEXT DAY DELIVERY';
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
                ImageColumn::make('image')
                    ->height(100)
                    ->width(100)
                    ->alignCenter(),

                TextColumn::make('name')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('posts_count')
                    ->counts('posts')
                    ->label('Posts')
                    ->alignCenter()
                    ->sortable(),

                SelectColumn::make('is_active')
                    ->label('Active')
                    ->alignCenter()
                    ->selectablePlaceholder(false)
                    ->options([
                        '0' => 'Inactive',
                        '1' => 'Active',
                    ])
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    DeleteAction::make()
                        ->before(function (Tables\Actions\DeleteAction $action, PostCategory $record) {

                            if ($record->posts()->exists()) {
                                Notification::make()
                                    ->danger()
                                    ->title('Failed to delete!')
                                    ->body('This category has associated posts and cannot be deleted.')
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
                            if ($record->posts()->exists()) {
                                Notification::make()
                                    ->danger()
                                    ->title("Failed to delete $record->name!")
                                    ->body('Category has products.')
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
            'index' => Pages\ListPostCategories::route('/'),
            'create' => Pages\CreatePostCategory::route('/create'),
            'edit' => Pages\EditPostCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Blog';
    }

    public static function getNavigationLabel(): string
    {
        return 'Categories';
    }

    public static function getNavigationSort(): ?int
    {
        return 6;  // Sort order for this resource
    }
}
