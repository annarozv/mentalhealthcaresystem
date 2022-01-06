@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($illness))
            <h3>
                @if(app()->getLocale() === 'en')
                    {{ $illness->illness_name }}
                @endif
                @if(app()->getLocale() === 'lv')
                    {{ $illness->illness_name_lv }}
                @endif
            </h3>
            @if(!Auth::guest() && Auth::user()->isAdmin())
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group" role="group">
                        <a class="btn btn-outline-primary" href="/illness/{{ $illness->id }}/edit">{{ __('messages.edit') }}</a>
                    </div>
                    <div class="btn-group ml-2" role="group">
                        <a class="btn btn-outline-success" href="/illness/{{ $illness->id }}/add_symptoms">{{ __('messages.add_symptoms') }}</a>
                    </div>
                    <div class="btn-group ml-2" role="group">
                        <form
                            method="POST"
                            onsubmit="return confirm(document.getElementById('confirm_message').value.toString());"
                            action="{{ route('illness.remove', $illness->id) }}"
                        >
                            @csrf
                            @method('post')
                            <input type="hidden" id="confirm_message" value="{{ __('messages.illness_delete_confirm') }}">
                            <input class="btn btn-outline-danger" type="submit" value="{{ __('messages.remove') }}">
                        </form>
                    </div>
                </div>
            @endif
            <hr>
            <p>
                @if(app()->getLocale() === 'en')
                    {{ $illness->description }}
                @endif
                @if(app()->getLocale() === 'lv')
                    {{ $illness->description_lv }}
                @endif
            </p>
            <h4>
                {{ __('messages.symptoms') }}
            </h4>
            @if(!empty($symptoms) && count($symptoms))
                <ul class="list-group">
                    @foreach($symptoms as $symptom)
                            <li class="list-group-item">
                                <a href="{{ route('symptom.details', $symptom->id) }}" class="text-dark">
                                    @if(app()->getLocale() === 'en')
                                        {{ $symptom->symptom_name }}
                                    @endif
                                    @if(app()->getLocale() === 'lv')
                                        {{ $symptom->symptom_name_lv }}
                                    @endif
                                </a>
                                @if(!Auth::guest() && Auth::user()->isAdmin())
                                    <form
                                        method="POST" class="form-inline d-inline float-right"
                                        action="{{ route('illness.remove_symptom', [$illness->id, $symptom->id]) }}"
                                    >
                                        @csrf
                                        @method('post')
                                        <input class="btn btn-outline-danger" type="submit" value="{{ __('messages.remove_symptom') }}">
                                    </form>
                                @endif
                            </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info">
                    {{ __('messages.no_symptoms') }}
                </div>
            @endif
        @else
            <div class="alert alert-info">
                {{ __('messages.no_info') }}
            </div>
        @endif
    </div>
@endsection
