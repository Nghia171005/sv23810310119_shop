<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(12)
                    ->schema([
                        Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(6),

                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'out_of_stock' => 'Out of stock',
                            ])
                            ->required()
                            ->default('draft')
                            ->columnSpan(6),

                        TextInput::make('name')
                            ->label('Tên sản phẩm')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set): void {
                                $set('slug', 'sv23810310119-' . Str::slug((string) $state));
                            })
                            ->columnSpan(6),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->readOnly()
                            ->columnSpan(6),

                        TextInput::make('price')
                            ->label('Giá')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->suffix('VNĐ')
                            ->columnSpan(4),

                        TextInput::make('stock_quantity')
                            ->label('Số lượng tồn')
                            ->numeric()
                            ->integer()
                            ->required()
                            ->minValue(0)
                            ->columnSpan(4),

                        TextInput::make('discount_percent')
                            ->label('Giảm giá (%)')
                            ->numeric()
                            ->integer()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100)
                            ->helperText('Trường sáng tạo: phần trăm giảm giá từ 0 đến 100.')
                            ->columnSpan(4),

                        FileUpload::make('image_path')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->maxFiles(1)
                            ->columnSpan(6),

                        Placeholder::make('final_price_preview')
                            ->label('Giá sau giảm')
                            ->content(function (callable $get): string {
                                $price = (float) ($get('price') ?? 0);
                                $discount = (int) ($get('discount_percent') ?? 0);
                                $final = $price * (100 - $discount) / 100;

                                return number_format($final, 0, ',', '.') . ' VNĐ';
                            })
                            ->columnSpan(6),

                        RichEditor::make('description')
                            ->label('Mô tả')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
