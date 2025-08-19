@extends('website.layout.app')

@section('title', __('messages.project_dashboard'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>{{ __('messages.project_dashboard') }}</h2>
            <p>{{ __('messages.welcome') }} to the project dashboard.</p>
        </div>
    </div>
</div>
@endsection