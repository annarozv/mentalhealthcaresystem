@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" class="form-horizontal" action="{{ route('therapists.filter') }}">
            @csrf
            @method('post')
            <div class="form-row justify-content-end">
                <input class="form-control w-auto" id="keyword" name="keyword" type="text" maxlength="30" placeholder="{{ __('messages.enter_keyword') }}">
                <input class="btn btn-dark ml-2" type="submit" value="{{ __('messages.search') }}">
            </div>
        </form>
        <hr>
        <h4 class="text-center">{{ __('messages.therapists') }}</h4>
        <hr class="mt-3">
        @if(Auth::check() && Auth::user()->isPatient() && Auth::user()->patient && Auth::user()->patient->is_active)
            <div class="d-flex justify-content-center">
                <a class="btn btn-dark" href="/therapist/find">{{ __('messages.individual_search') }}</a>
            </div>
            <hr>
        @endif
        @if(!empty($therapists) && count($therapists))
            <div class="d-lg-flex d-xl-flex flex-row flex-wrap">
                @foreach($therapists as $therapist)
                    <div class="card w-sm-100 w-md-100 w-lg-50 w-xl-50 m-1">
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
                            <a href="/therapist/{{ $therapist->id }}/info" class="btn btn-outline-dark float-right">
                                {{ __('messages.therapist_info') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info w-50">
                {{ __('messages.no_therapists') }}
            </div>
        @endif
    </div>
@endsection
