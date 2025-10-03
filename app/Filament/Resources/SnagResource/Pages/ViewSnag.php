<?php

namespace App\Filament\Resources\SnagResource\Pages;

use App\Filament\Resources\SnagResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;

class ViewSnag extends ViewRecord
{
    protected static string $resource = SnagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('resolve')
                ->label('Mark Resolved')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status !== 'resolved')
                ->action(function () {
                    $this->record->update([
                        'status' => 'resolved',
                        'resolved_at' => now(),
                        'resolved_by' => auth()->id(),
                    ]);
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Snag Details')
                    ->schema([
                        TextEntry::make('snag_number')
                            ->label('Snag Number')
                            ->badge(),
                        TextEntry::make('title')
                            ->label('Title')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->placeholder('No description provided'),
                        TextEntry::make('project.project_title')
                            ->label('Project'),
                        TextEntry::make('phase.title')
                            ->label('Phase')
                            ->placeholder('No phase assigned'),
                        TextEntry::make('location')
                            ->label('Location'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : 'Open'),
                        TextEntry::make('reporter.name')
                            ->label('Reported By')
                            ->placeholder('Unknown'),
                        TextEntry::make('assignedUser.name')
                            ->label('Assigned To')
                            ->placeholder('Unassigned'),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                    ])->columns(2),

                Section::make('Comments')
                    ->schema([
                        TextEntry::make('comment')
                            ->label('')
                            ->placeholder('No comments added')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => !empty($record->comment)),

                Section::make('Resolution')
                    ->schema([
                        TextEntry::make('resolved_at')
                            ->label('Resolved At')
                            ->dateTime(),
                        TextEntry::make('resolution_notes')
                            ->label('Resolution Notes')
                            ->columnSpanFull()
                            ->placeholder('No resolution notes'),
                    ])
                    ->visible(fn ($record) => $record->status === 'resolved'),
            ]);
    }
}