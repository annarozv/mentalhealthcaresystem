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
                            {{ __('messages.patient') }}:
                            <a class="text-dark" href="/patient/{{ $request->patient->id }}/info">
                            {{ $request->patient->user->name }} {{ $request->patient->user->surname }}
                            </a>
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
                        @if($request->type->type === \App\Models\RequestType::CONNECTION)
                            {{-- if user is connected with patient, he will be able to disconnect from him --}}
                            @if(
                                $request->status->status === \App\Models\Status::APPROVED
                                || $request->status->status === \App\Models\Status::INITIATED
                            )
                                <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('disconnect_confirm_message').value.toString());" action="{{ route('therapist.remove.request', $request->id) }}">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" id="disconnect_confirm_message" value="{{ __('messages.disconnect_confirm_message_pat') }}">
                                    <input class="btn btn-outline-danger d-inline float-right ml-2" type="submit"
                                           value="@if($request->status->status === \App\Models\Status::APPROVED) {{ __('messages.disconnect_patient') }} @endif @if($request->status->status === \App\Models\Status::INITIATED) {{ __('messages.remove_request') }} @endif"
                                    >
                                </form>
                                @if($request->status->status === \App\Models\Status::INITIATED)
                                    <form class="form-inline d-inline" method="POST" action="{{ route('therapist.approve.request', $request->id) }}">
                                        @method('post')
                                        @csrf
                                        <input class="btn btn-outline-success d-inline float-right ml-2" type="submit"
                                               value="{{ __('messages.approve_request') }}">
                                    </form>
                                @endif
                            @endif
                        @endif
                        @if($request->type->type === \App\Models\RequestType::FEEDBACK)
                            @if($request->status->status === \App\Models\Status::INITIATED)
                                <form class="form-inline d-inline" method="POST" action="{{ route('therapist.remove.request', $request->id) }}">
                                    @method('post')
                                    @csrf
                                    <input class="btn btn-outline-danger d-inline float-right" type="submit" value="{{ __('messages.remove_request') }}">
                                </form>
                                <form class="form-inline d-inline" method="POST" action="{{ route('therapist.approve.request', $request->id) }}">
                                    @method('post')
                                    @csrf
                                    <input class="btn btn-outline-success d-inline float-right mr-2" type="submit" value="{{ __('messages.approve_request') }}">
                                </form>
                            @endif
                        @endif
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
