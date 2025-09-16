<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Role;
use App\Models\Permission;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionMatrix extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static string $view = 'filament.pages.permission-matrix';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Permission Matrix';

    public $permissions = [];
    public $roles = [];
    public $matrix = [];

    public function mount(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        $this->roles = Role::where('is_active', true)->get();
        $this->permissions = Permission::where('is_active', true)
            ->orderBy('module')
            ->orderBy('action')
            ->get()
            ->groupBy('module');

        // Build matrix
        $this->matrix = [];
        foreach ($this->roles as $role) {
            $rolePermissions = \DB::table('role_permissions')
                ->where('role_id', $role->id)
                ->pluck('permission_id')
                ->toArray();
            foreach ($this->permissions as $module => $modulePermissions) {
                foreach ($modulePermissions as $permission) {
                    $this->matrix[$role->id][$permission->id] = in_array($permission->id, $rolePermissions);
                }
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action('saveMatrix'),
            Action::make('reset')
                ->label('Reset to Default')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->action('resetMatrix'),
        ];
    }

    public function saveMatrix(): void
    {
        try {
            foreach ($this->matrix as $roleId => $permissions) {
                $role = Role::find($roleId);
                if ($role) {
                    $permissionIds = [];
                    foreach ($permissions as $permissionId => $hasPermission) {
                        if ($hasPermission) {
                            $permissionIds[] = $permissionId;
                        }
                    }
                    $role->permissions()->sync($permissionIds);
                    
                    // Clear cache for this role
                    Cache::flush();
                }
            }

            Notification::make()
                ->title('Permissions Updated')
                ->body('Permission matrix has been saved successfully.')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to save permissions: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function resetMatrix(): void
    {
        // Reset to default permissions (you can implement this based on your needs)
        $this->loadData();
        
        Notification::make()
            ->title('Matrix Reset')
            ->body('Permission matrix has been reset to default values.')
            ->success()
            ->send();
    }

    public function togglePermission($roleId, $permissionId): void
    {
        $this->matrix[$roleId][$permissionId] = !($this->matrix[$roleId][$permissionId] ?? false);
    }

    public function toggleRoleModule($roleId, $module): void
    {
        $modulePermissions = $this->permissions[$module] ?? collect();
        $allEnabled = true;
        
        foreach ($modulePermissions as $permission) {
            if (!($this->matrix[$roleId][$permission->id] ?? false)) {
                $allEnabled = false;
                break;
            }
        }
        
        foreach ($modulePermissions as $permission) {
            $this->matrix[$roleId][$permission->id] = !$allEnabled;
        }
    }

    public function togglePermissionForAllRoles($permissionId): void
    {
        $allEnabled = true;
        
        foreach ($this->roles as $role) {
            if (!($this->matrix[$role->id][$permissionId] ?? false)) {
                $allEnabled = false;
                break;
            }
        }
        
        foreach ($this->roles as $role) {
            $this->matrix[$role->id][$permissionId] = !$allEnabled;
        }
    }
}