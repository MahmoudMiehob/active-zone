<?php

namespace App\Filament\Superadmin\Resources\QuestionAnswerResource\Pages;

use App\Filament\Superadmin\Resources\QuestionAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestionAnswer extends EditRecord
{
    protected static string $resource = QuestionAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
