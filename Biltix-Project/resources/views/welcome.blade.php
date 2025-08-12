@extends('layouts.app')

@section('title', __t('common.home') . ' - ' . config('app.name'))

@section('meta_description', __t('common.description', [], null, 'Welcome to our multilingual Laravel application with RTL support'))

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 mb-4">
                    {{ __t('common.welcome', [], null, 'Welcome') }} 
                    <span class="text-primary">{{ config('app.name') }}</span>
                </h1>
                <p class="lead">
                    {{ __t('common.multilingual_description', [], null, 'This is a demonstration of our multilingual Laravel application with full RTL support for Arabic language.') }}
                </p>
                
                <!-- Language Demo -->
                <div class="alert alert-info">
                    <h5>{{ __t('common.current_language') }}: 
                        <strong>{{ $availableLocales[$currentLocale]['native'] ?? 'Unknown' }}</strong>
                        <span class="badge bg-primary ms-2">{{ strtoupper($currentLocale) }}</span>
                        @if($isRtl)
                            <span class="badge bg-warning ms-2">RTL</span>
                        @endif
                    </h5>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Features Section -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-language fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">{{ __t('common.multilingual_support') }}</h5>
                    <p class="card-text">
                        {{ __t('common.multilingual_description', [], null, 'Full support for multiple languages with automatic detection and switching.') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-arrows-alt-h fa-3x text-success mb-3"></i>
                    <h5 class="card-title">{{ __t('common.rtl_support') }}</h5>
                    <p class="card-text">
                        {{ __t('common.rtl_description', [], null, 'Automatic RTL layout support for Arabic and other right-to-left languages.') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-mobile-alt fa-3x text-info mb-3"></i>
                    <h5 class="card-title">{{ __t('common.api_support') }}</h5>
                    <p class="card-text">
                        {{ __t('common.api_description', [], null, 'Complete API support for mobile applications with localized responses.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Demo Section -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __t('common.demo_features') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Language Information -->
                    <div class="mb-4">
                        <h6>{{ __t('common.language_info') }}:</h6>
                        <ul class="list-unstyled">
                            <li><strong>{{ __t('common.current_language') }}:</strong> {{ $availableLocales[$currentLocale]['native'] ?? 'Unknown' }}</li>
                            <li><strong>{{ __t('common.direction') }}:</strong> 
                                @if($isRtl)
                                    <span class="badge bg-warning">{{ __t('common.right_to_left') }}</span>
                                @else
                                    <span class="badge bg-info">{{ __t('common.left_to_right') }}</span>
                                @endif
                            </li>
                            <li><strong>{{ __t('common.available_languages') }}:</strong>
                                @foreach($availableLocales as $locale => $data)
                                    <span class="badge bg-secondary me-1">{{ $data['flag'] }} {{ $data['native'] }}</span>
                                @endforeach
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Sample Form -->
                    <form class="mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">{{ __t('common.name') }}</label>
                                <input type="text" class="form-control" id="name" placeholder="{{ __t('common.enter_name', [], null, 'Enter your name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __t('common.email') }}</label>
                                <input type="email" class="form-control" id="email" placeholder="{{ __t('common.enter_email', [], null, 'Enter your email') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">{{ __t('common.message', [], null, 'Message') }}</label>
                            <textarea class="form-control" id="message" rows="3" placeholder="{{ __t('common.enter_message', [], null, 'Enter your message') }}"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __t('common.submit') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __t('common.reset') }}</button>
                    </form>
                    
                    <!-- Sample Data Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __t('common.name') }}</th>
                                    <th>{{ __t('common.email') }}</th>
                                    <th>{{ __t('common.status') }}</th>
                                    <th>{{ __t('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ __t('common.sample_user_1', [], null, 'John Doe') }}</td>
                                    <td>john@example.com</td>
                                    <td><span class="badge bg-success">{{ __t('common.active') }}</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">{{ __t('common.edit') }}</button>
                                        <button class="btn btn-sm btn-danger">{{ __t('common.delete') }}</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __t('common.sample_user_2', [], null, 'Jane Smith') }}</td>
                                    <td>jane@example.com</td>
                                    <td><span class="badge bg-warning">{{ __t('common.inactive') }}</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">{{ __t('common.edit') }}</button>
                                        <button class="btn btn-sm btn-danger">{{ __t('common.delete') }}</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- API Demo Section -->
    <div class="row mt-5">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __t('common.api_demo') }}</h5>
                </div>
                <div class="card-body">
                    <p>{{ __t('common.api_demo_description', [], null, 'Test the localization API endpoints below:') }}</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>{{ __t('common.available_endpoints') }}:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <code>GET /api/v1/locales</code>
                                    <small class="text-muted d-block">{{ __t('common.get_available_locales') }}</small>
                                </li>
                                <li class="list-group-item">
                                    <code>GET /api/v1/translations/{namespace}</code>
                                    <small class="text-muted d-block">{{ __t('common.get_translations') }}</small>
                                </li>
                                <li class="list-group-item">
                                    <code>GET /api/v1/translations-all</code>
                                    <small class="text-muted d-block">{{ __t('common.get_all_translations') }}</small>
                                </li>
                                <li class="list-group-item">
                                    <code>POST /api/v1/lang/switch/{locale}</code>
                                    <small class="text-muted d-block">{{ __t('common.switch_language') }}</small>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __t('common.test_api') }}:</h6>
                            <button class="btn btn-outline-primary btn-sm mb-2 d-block" onclick="testApi('locales')">
                                {{ __t('common.test') }} /api/v1/locales
                            </button>
                            <button class="btn btn-outline-primary btn-sm mb-2 d-block" onclick="testApi('translations/common')">
                                {{ __t('common.test') }} /api/v1/translations/common
                            </button>
                            <button class="btn btn-outline-primary btn-sm mb-2 d-block" onclick="testApi('translations-all')">
                                {{ __t('common.test') }} /api/v1/translations-all
                            </button>
                            
                            <div id="api-result" class="mt-3" style="display: none;">
                                <h6>{{ __t('common.api_response') }}:</h6>
                                <pre class="bg-light p-2 rounded"><code id="api-response"></code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function testApi(endpoint) {
    try {
        const response = await fetch(`/api/v1/${endpoint}?locale={{ $currentLocale }}`);
        const data = await response.json();
        
        document.getElementById('api-response').textContent = JSON.stringify(data, null, 2);
        document.getElementById('api-result').style.display = 'block';
    } catch (error) {
        document.getElementById('api-response').textContent = 'Error: ' + error.message;
        document.getElementById('api-result').style.display = 'block';
    }
}
</script>
@endsection
