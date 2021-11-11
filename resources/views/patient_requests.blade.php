@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>{{ __('messages.my_requests') }}</h3>
        <hr>
        @if(!empty($requests) && count($requests))
            @foreach($requests as $request)
                <div class="card w-75 m-1">
                    <div class="card-body">
                        <h5>
                            {{ __('messages.therapist') }}: {{ $request->therapist->user->name }} {{ $request->therapist->user->surname }}
                        </h5>
                        <h6>
                            {{ __('messages.request_type') }}:
                            @if(app()->getLocale() === 'en')
                                {{ $request->type->type }}
                            @endif
                            @if(app()->getLocale() === 'lv')
                                {{ $request->type->type_lv }}
                            @endif
                        </h6>
                        <h6>
                            {{ __('messages.request_status') }}:
                            @if(app()->getLocale() === 'en')
                                {{ $request->status->status }}
                            @endif
                            @if(app()->getLocale() === 'lv')
                                {{ $request->status->status_lv }}
                            @endif
                        </h6>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info w-75">
                {{ __('messages.no_requests') }}
            </div>
        @endif
    </div>
@endsection
