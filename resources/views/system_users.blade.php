@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="d-inline">{{ __('messages.system_users') }}</h3>
        <form method="POST" class="form-horizontal d-flex form-inline justify-content-end float-right mr-1" action="{{ route('users.search') }}">
            @csrf
            @method('post')
            <div class="form-row justify-content-end">
                <input class="form-control w-50" id="keyword" name="keyword" type="text" maxlength="30" placeholder="{{ __('messages.enter_keyword') }}">
                <input class="btn btn-dark ml-2" type="submit" value="{{ __('messages.search') }}">
            </div>
        </form>
        <hr>
        <h4>{{ __('messages.therapists') }}</h4>
        <hr>
        @if(!empty($therapists) && count($therapists))
            @foreach($therapists as $therapist)
                <div class="card m-1">
                    <div class="card-body">
                        <h5 class="card-title d-inline">{{ $therapist->name }} {{ $therapist->surname }}</h5>
                        <h5 class="d-inline float-right">
                            @if($therapist->is_active)
                                <span class="text-success">{{ __('messages.active') }}</span>
                            @else
                                <span class="text-danger">{{ __('messages.deactivated') }}</span>
                            @endif
                        </h5>
                        <h6 class="text-muted">{{ $therapist->email }}</h6>
                        <div class="mt-3">
                            @if (!empty($therapist->therapist))
                                <a class="btn btn-outline-dark d-inline" href="/therapist/{{ $therapist->therapist->id }}/info">
                                    {{ __('messages.therapist_info') }}
                                </a>
                            @endif
                            @if($therapist->is_active)
                                <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('deactivate_confirm_message').value.toString());" action="{{ route('deactivate.user', $therapist->id) }}">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" id="deactivate_confirm_message" value="{{ __('messages.deactivate_confirm_message') }}">
                                    <input class="btn btn-outline-danger d-inline float-right" type="submit" value="{{ __('messages.deactivate') }}">
                                </form>
                            @else
                                <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('reactivate_confirm_message').value.toString());" action="{{ route('reactivate.user', $therapist->id) }}">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" id="reactivate_confirm_message" value="{{ __('messages.reactivate_confirm_message') }}">
                                    <input class="btn btn-outline-success d-inline float-right" type="submit" value="{{ __('messages.reactivate') }}">
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info w-75">
                {{ __('messages.no_therapist_records') }}
            </div>
        @endif
        <hr>
        <h4>{{ __('messages.patients') }}</h4>
        <hr>
        @if(!empty($patients) && count($patients))
            @foreach($patients as $patient)
                <div class="card m-1">
                    <div class="card-body">
                        <h5 class="card-title d-inline">{{ $patient->name }} {{ $patient->surname }}</h5>
                        <h5 class="d-inline float-right">
                            @if($patient->is_active)
                                <span class="text-success">{{ __('messages.active') }}</span>
                            @else
                                <span class="text-danger">{{ __('messages.deactivated') }}</span>
                            @endif
                        </h5>
                        <h6 class="text-muted">{{ $patient->email }}</h6>
                        <div class="mt-3">
                            @if (!empty($patient->patient))
                                <a class="btn btn-outline-dark d-inline" href="/patient/{{ $patient->patient->id }}/info">
                                    {{ __('messages.patient_info') }}
                                </a>
                            @endif
                            @if($patient->is_active)
                                <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('deactivate_confirm_message').value.toString());" action="{{ route('deactivate.user', $patient->id) }}">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" id="deactivate_confirm_message" value="{{ __('messages.deactivate_confirm_message') }}">
                                    <input class="btn btn-outline-danger d-inline float-right" type="submit" value="{{ __('messages.deactivate') }}">
                                </form>
                            @else
                                <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('reactivate_confirm_message').value.toString());" action="{{ route('reactivate.user', $patient->id) }}">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" id="reactivate_confirm_message" value="{{ __('messages.reactivate_confirm_message') }}">
                                    <input class="btn btn-outline-success d-inline float-right" type="submit" value="{{ __('messages.reactivate') }}">
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info w-75">
                {{ __('messages.no_patients') }}
            </div>
        @endif
    </div>
@endsection
