@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($therapists) && count($therapists))
            <h4 class="d-inline mb-0 mt-5">{{ __('messages.my_therapists') }}</h4>
            <hr>
            @foreach($therapists as $therapist)
                <div class="card w-75 m-1">
                    <div class="card-body">
                        <h5 class="card-title">{{ $therapist->user->name }} {{ $therapist->user->surname }}</h5>
                        <span>
                        <b>{{ __('messages.date_of_birth') }}:</b>
                        {{ DateTime::createFromFormat('Y-m-d', $therapist->date_of_birth)->format('F j, Y')  }}
                        </span>
                                <br>
                                <span>
                            <b>{{ __('messages.gender') }}:</b>
                            @if(app()->getLocale() === 'en')
                                        {{ $therapist->gender->gender }}
                                    @endif
                                    @if(app()->getLocale() === 'lv')
                                        {{ $therapist->gender->gender_lv }}
                                    @endif
                        </span>
                        <p class="card-text overflow-hidden cut-text" style="text-overflow: ellipsis; white-space: nowrap;">
                            <b>{{ __('messages.specialization') }}:</b>
                            {{ $therapist->specialization }}
                        </p>
                        <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('disconnect_confirm_message').value.toString());" action="{{ route('disconnect.therapist', $therapist->id) }}">
                            @method('post')
                            @csrf
                            <input type="hidden" id="disconnect_confirm_message" value="{{ __('messages.disconnect_confirm_message') }}">
                            <input class="btn btn-outline-danger d-inline float-right ml-2" type="submit" value="{{ __('messages.disconnect_therapist') }}">
                        </form>
                        <a href="/therapist/{{ $therapist->id }}/info" class="btn btn-outline-dark float-right">
                            {{ __('messages.therapist_info') }}
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info w-75">
                {{ __('messages.no_connected_therapists') }}
            </div>
            <a class="btn btn-outline-dark" href="">{{ __('messages.find_therapist') }}</a>
        @endif
    </div>
@endsection
