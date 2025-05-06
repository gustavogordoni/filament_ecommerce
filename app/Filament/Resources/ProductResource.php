<?php

namespace App\Filament\Resources;

use Dom\Text;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $label = 'Produto';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Informações do Produto')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nome')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord: true)
                                ->maxLength(255),

                            MarkdownEditor::make('description')
                                ->label('Descrição')
                                ->columnSpanFull()
                                ->fileAttachmentsDirectory('products'),

                        ])->columns(2),

                    Section::make('Imagens')
                        ->schema([
                            FileUpload::make('images')
                                ->label('Imagens')
                                ->directory('products')
                                ->multiple()
                                ->maxFiles(5)
                                ->reorderable(),
                        ])
                ])->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make('Preço')
                            ->schema([
                                TextInput::make('price')
                                    ->label('Preço')
                                    ->numeric()
                                    ->required()
                                    ->prefix('R$'),
                            ]),

                        Section::make('Associação')
                            ->schema([
                                Select::make('category_id')
                                    ->label('Categoria')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->relationship('category', 'name'),

                                Select::make('brand_id')
                                    ->label('Marca')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->relationship('brand', 'name')
                            ]),

                        Section::make('Status')
                            ->schema([
                                Toggle::make('in_stock')
                                    ->label('Em estoque')
                                    ->required()
                                    ->default(true),

                                Toggle::make('is_active')
                                    ->label('Ativo')
                                    ->required()
                                    ->default(true),

                                Toggle::make('is_featured')
                                    ->label('Destaque')
                                    ->required(),

                                Toggle::make('on_sale')
                                    ->label('À venda')
                                    ->required(),
                            ])

                    ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->sortable(),

                TextColumn::make('brand.name')
                    ->label('Marca')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->label('Destaque')
                    ->boolean(),

                IconColumn::make('on_sale')
                    ->label('À venda')
                    ->boolean(),

                IconColumn::make('in_stock')
                    ->label('Em estoque')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name'),

                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
            ])
            ->actions([
                ActionGroup::make([
                  ViewAction::make(),
                  EditAction::make(),
                  DeleteAction::make(),
                ])               
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
