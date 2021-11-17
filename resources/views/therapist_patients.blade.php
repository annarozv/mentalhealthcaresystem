@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($patients) && count($patients))
            <h4 class="d-inline mb-0 mt-5">{{ __('messages.my_patients') }}</h4>
            <hr>
            @foreach($patients as $patient)
                <div class="card w-75 m-1">
                    <div class="card-body">
                        <h5 class="card-title">{{ $patient->user->name }} {{ $patient->user->surname }}</h5>
                        <span>
                        <b>{{ __('messages.date_of_birth') }}:</b>
                        {{ DateTime::createFromFormat('Y-m-d', $patient->date_of_birth)->format('F j, Y')  }}
                        </span>
                        <br>
                        <span>
                            <b>{{ __('messages.gender') }}:</b>
                            @if(app()->getLocale() === 'en')
                                {{ $patient->gender->gender }}
                            @endif
                            @if(app()->getLocale() === 'lv')
                                {{ $patient->gender->gender_lv }}
                            @endif
                        </span>
                        <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('disconnect_confirm_message').value.toString());" action="{{ route('disconnect.patient', $patient->id) }}">
                            @method('post')
                            @csrf
                            <input type="hidden" id="disconnect_confirm_message" value="{{ __('messages.disconnect_confirm_message_pat') }}">
                            <input class="btn btn-outline-danger d-inline float-right m-1" type="submit" value="{{ __('messages.disconnect_patient') }}">
                        </form>
                        <a href="/patient/{{ $patient->id }}/info" class="btn btn-outline-dark float-right m-1">
                            {{ __('messages.patient_info') }}
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info w-75">
                {{ __('messages.no_connected_patients') }}
            </div>
        @endif
    </div>
@endsection
