@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>{{ __('messages.search_results') }}</h4>
        <hr>
        @if(!empty($therapists) && count($therapists))
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
                        <a href="/therapist/{{ $therapist->id }}/info" class="btn btn-outline-dark float-right m-1">
                            {{ __('messages.therapist_info') }}
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info w-75">
                {{ __('messages.no_found_therapists') }}
            </div>
            <a class="btn btn-dark" href="/therapists">{{ __('messages.therapist_list') }}</a>
        @endif
    </div>
@endsection
