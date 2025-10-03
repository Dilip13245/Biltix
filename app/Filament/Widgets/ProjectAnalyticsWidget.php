<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Project;
use App\Models\Task;
use App\Models\Inspection;

class ProjectAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Project Analytics';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return __('filament.dashboard.project_analytics');
    }

    protected function getData(): array
    {
        $projectsByStatus = Project::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $tasksByStatus = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $inspectionsByStatus = Inspection::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('filament.dashboard.projects'),
                    'data' => [
                        $projectsByStatus['planning'] ?? 0,
                        $projectsByStatus['active'] ?? 0,
                        $projectsByStatus['completed'] ?? 0,
                        $projectsByStatus['on_hold'] ?? 0,
                    ],
                    'backgroundColor' => ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'],
                ],
                [
                    'label' => __('filament.dashboard.tasks'),
                    'data' => [
                        $tasksByStatus['pending'] ?? 0,
                        $tasksByStatus['in_progress'] ?? 0,
                        $tasksByStatus['completed'] ?? 0,
                        $tasksByStatus['cancelled'] ?? 0,
                    ],
                    'backgroundColor' => ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'],
                ],
                [
                    'label' => __('filament.dashboard.inspections'),
                    'data' => [
                        $inspectionsByStatus['scheduled'] ?? 0,
                        $inspectionsByStatus['in_progress'] ?? 0,
                        $inspectionsByStatus['completed'] ?? 0,
                        $inspectionsByStatus['failed'] ?? 0,
                    ],
                    'backgroundColor' => ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'],
                ],
            ],
            'labels' => [
                __('filament.options.planning'),
                __('filament.options.active'),
                __('filament.options.completed'),
                __('filament.options.on_hold'),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}