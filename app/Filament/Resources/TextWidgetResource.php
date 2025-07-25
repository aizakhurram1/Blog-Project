<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TextWidgetResource\Pages;
use App\Models\TextWidget;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class TextWidgetResource extends Resource
{
    protected static ?string $model = TextWidget::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('image')
                    ->maxLength(2048),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(2048),
                Forms\Components\RichEditor::make('content'),
                Forms\Components\Toggle::make('active')
                    ->required(),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('image'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('content'),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTextWidgets::route('/'),
            'create' => Pages\CreateTextWidget::route('/create'),
            // 'view' => Pages\ViewTextWidget::route('/{record}'),
            'edit' => Pages\EditTextWidget::route('/{record}/edit'),
        ];
    }
}
