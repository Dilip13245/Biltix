<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Models\File;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;

class ListFiles extends ListRecords
{
    protected static string $resource = FileResource::class;

    protected static string $view = 'filament.resources.file-resource.pages.list-files';

    public ?int $selectedProjectId = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => $this->selectedProjectId !== null),
            Actions\Action::make('back_to_projects')
                ->label(__('filament.actions.back_to_projects'))
                ->icon('heroicon-o-arrow-left')
                ->url(fn () => static::getUrl())
                ->visible(fn () => $this->selectedProjectId !== null),
        ];
    }

    public function mount(): void
    {
        parent::mount();
        $this->selectedProjectId = request()->query('project_id');
    }

    protected function getTableQuery(): Builder
    {
        if ($this->selectedProjectId) {
            return parent::getTableQuery()->where('project_id', $this->selectedProjectId);
        }
        
        // Return empty query when showing project folders
        return File::query()->whereRaw('1 = 0');
    }

    public function getProjects()
    {
        return Project::active()
            ->withCount(['files' => function ($query) {
                $query->where('is_deleted', false);
            }])
            ->get();
    }

    public function getSelectedProject()
    {
        if ($this->selectedProjectId) {
            return Project::find($this->selectedProjectId);
        }
        return null;
    }

    protected function getViewData(): array
    {
        return [
            'projects' => $this->getProjects(),
            'selectedProject' => $this->getSelectedProject(),
            'selectedProjectId' => $this->selectedProjectId,
        ];
    }
}