@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($patient))
            <div class="row">
                <div class="col-6 col-md-4">
                    <img class="img-fluid img-thumbnail mb-3" src="{{ url('images/' . $patient->profile_picture) }}" alt="profile picture"/>
                </div>
                <div class="col-12 col-md-8">
                    <h5>{{ $patient->user->name }} {{ $patient->user->surname }}</h5>
                    @if($patient->date_of_birth)
                        <b>{{ __('messages.date_of_birth') }}:</b>
                        {{ DateTime::createFromFormat('Y-m-d', $patient->date_of_birth)->format('F j, Y')  }}
                        <br>
                    @endif
                    <b>{{ __('messages.gender') }}:</b>
                    @if(app()->getLocale() === 'en')
                        {{ $patient->gender->gender }}
                    @endif
                    @if(app()->getLocale() === 'lv')
                        {{ $patient->gender->gender_lv }}
                    @endif
                    <hr>
                    <h5>{{ __('messages.additional_information') }}</h5>
                    <p>
                        {{ $patient->additional_information }}
                    </p>
                </div>
            </div>
                @if(Auth::id() === $patient->user->id)
                    <hr>
                    <div class="btn-group" role="group">
                        <a class="btn btn-outline-dark" href="/patient/edit">{{ __('messages.edit') }}</a>
                    </div>
                    <div class="btn-group ml-2" role="group">
                        <form method="POST" onsubmit="return confirm(document.getElementById('confirm_message').value.toString());" action="{{ route('patient.remove', $patient->id) }}">
                            @csrf
                            @method('post')
                            <input type="hidden" id="confirm_message" value="{{ __('patients.patient_remove_confirm') }}">
                            <input class="btn btn-outline-danger" type="submit" value="{{ __('patients.patient_remove') }}">
                        </form>
                    </div>
                @endif
        @else
            <div class="alert alert-info w-50">
                {{ __('messages.no_info') }}
            </div>
        @endif
    </div>
@endsection
