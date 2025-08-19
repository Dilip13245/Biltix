<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Test - {{ __('messages.dashboard') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @if(is_rtl())
        <link rel="stylesheet" href="{{ asset('admin/css/rtl.css') }}">
    @endif
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ __('messages.dashboard') }}</h3>
                        <div class="language-switcher">
                            <select onchange="window.location.href='{{ route('lang.switch', '') }}/'+this.value" class="form-select" style="width: auto;">
                                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ is_rtl() ? 'selected' : '' }}>العربية</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4>{{ __('messages.welcome_back', ['name' => 'Admin']) }}</h4>
                        <p>{{ __('messages.whats_happening') }}</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5>{{ __('messages.total_projects') }}</h5>
                                        <h2>12</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5>{{ __('messages.active_projects') }}</h5>
                                        <h2>8</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5>{{ __('messages.total_users') }}</h5>
                                        <h2>25</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5>{{ __('messages.pending_tasks') }}</h5>
                                        <h2>5</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5>{{ __('messages.quick_actions') }}</h5>
                            <div class="btn-group" role="group">
                                <button class="btn btn-primary">{{ __('messages.new_project') }}</button>
                                <button class="btn btn-success">{{ __('messages.add_user') }}</button>
                                <button class="btn btn-info">{{ __('messages.generate_report') }}</button>
                                <button class="btn btn-secondary">{{ __('messages.settings') }}</button>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <p><strong>Current Locale:</strong> {{ app()->getLocale() }}</p>
                            <p><strong>Direction:</strong> {{ dir_class() === 'rtl' ? 'RTL' : 'LTR' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>