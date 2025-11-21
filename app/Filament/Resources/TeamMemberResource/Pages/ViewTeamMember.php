<?php

namespace App\Filament\Resources\TeamMemberResource\Pages;

use App\Filament\Resources\TeamMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Support\Enums\FontWeight;

class ViewTeamMember extends ViewRecord
{
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('filament.fields.team_member_details'))
                    ->schema([
                        TextEntry::make('project.project_title')
                            ->label(__('filament.fields.project')),
                        TextEntry::make('user.name')
                            ->label(__('filament.fields.member_name'))
                            ->weight(FontWeight::Bold),
                        TextEntry::make('user.email')
                            ->label(__('filament.fields.email')),
                        TextEntry::make('role_in_project')
                            ->label(__('filament.fields.project_role'))
                            ->badge(),
                        TextEntry::make('assignedBy.name')
                            ->label(__('filament.fields.assigned_by')),
                        TextEntry::make('assigned_at')
                            ->label(__('filament.fields.assigned_date'))
                            ->dateTime(),
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.active'))
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])->columns(2),
            ]);
    }
}