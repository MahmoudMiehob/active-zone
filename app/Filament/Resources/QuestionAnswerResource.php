<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\QuestionAnswer;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\QuestionAnswerResource\Pages;
use App\Filament\Resources\QuestionAnswerResource\RelationManagers;

class QuestionAnswerResource extends Resource
{
    protected static ?string $model = QuestionAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.question.label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question')->label(__('filament-panels::layout.actions.table.question.label')),
                Forms\Components\TextInput::make('answer')->label(__('filament-panels::layout.actions.table.answer.label')),
                Hidden::make('provider_id')->default(auth()->user()->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        $userId = auth()->user()->id;

        return $table
            ->modifyQueryUsing(function (Builder $query) use ($userId) {
                $query->where('provider_id', $userId);
            })
            ->columns([
                Tables\Columns\TextColumn::make('question')->label(__('filament-panels::layout.actions.table.question.label'))->searchable(),
                Tables\Columns\TextColumn::make('answer')->label(__('filament-panels::layout.actions.table.answer.label'))->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListQuestionAnswers::route('/'),
            'create' => Pages\CreateQuestionAnswer::route('/create'),
            'edit' => Pages\EditQuestionAnswer::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.question.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.question.label');
    }
}
