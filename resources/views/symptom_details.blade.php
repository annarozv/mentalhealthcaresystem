@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($symptom))
            <h3>
                @if(app()->getLocale() === 'en')
                    {{ $symptom->symptom_name }}
                @endif
                @if(app()->getLocale() === 'lv')
                    {{ $symptom->symptom_name_lv }}
                @endif
            </h3>
            @if(!Auth::guest() && Auth::user()->isAdmin())
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group" role="group">
                        <form method="GET" class="form-inline" action="{{ route('symptom.edit', $symptom->id) }}">
                            <input class="btn btn-outline-primary" type="submit" value="{{ __('messages.edit') }}">
                        </form>
                    </div>
                    <div class="btn-group ml-2" role="group">
                        <form method="POST" onsubmit="return confirm(document.getElementById('confirm_message').value.toString());" action="{{ route('symptom.remove', $symptom->id) }}">
                            @csrf
                            @method('post')
                            <input type="hidden" id="confirm_message" value="{{ __('messages.symptom_delete_confirm') }}">
                            <input class="btn btn-outline-danger" type="submit" value="{{ __('messages.remove') }}">
                        </form>
                    </div>
                </div>
            @endif
            <hr>
            <p>
                @if(app()->getLocale() === 'en')
                    {{ $symptom->description }}
                @endif
                @if(app()->getLocale() === 'lv')
                    {{ $symptom->description_lv }}
                @endif
            </p>
            <h4>
                {{ __('messages.illnesses_title') }}
            </h4>
            @if(!empty($illnesses) && count($illnesses))
                <ul class="list-group">
                    @foreach($illnesses as $illness)
                            <li class="list-group-item">
                                <a href="{{ route('illness.details', $illness->id) }}" class="text-dark">
                                    @if(app()->getLocale() === 'en')
                                        {{ $illness->illness_name }}
                                    @endif
                                    @if(app()->getLocale() === 'lv')
                                        {{ $illness->illness_name_lv }}
                                    @endif
                                </a>
                            </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info">
                    {{ __('messages.no_illnesses') }}
                </div>
            @endif
        @else
            <div class="alert alert-info">
                {{ __('messages.no_info') }}
            </div>
        @endif
    </div>
@endsection
