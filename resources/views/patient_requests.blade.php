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
                            {{ __('messages.therapist') }}:
                            {{ $request->therapist->user->name }} {{ $request->therapist->user->surname }}
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
                            {{-- if user is connected with therapist, he will be able to remove request --}}
                            @if($request->status->status === \App\Models\Status::APPROVED)
                                <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('disconnect_confirm_message').value.toString());" action="{{ route('disconnect.therapist', $request->therapist_id) }}">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" id="disconnect_confirm_message" value="{{ __('messages.disconnect_confirm_message') }}">
                                    <input class="btn btn-outline-danger d-inline float-right ml-2" type="submit" value="{{ __('messages.disconnect_therapist') }}">
                                </form>
                            @endif
                            @if($request->status->status === \App\Models\Status::INITIATED)
                                <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('disconnect_confirm_message').value.toString());" action="{{ route('disconnect.therapist', $request->therapist_id) }}">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" id="disconnect_confirm_message" value="{{ __('messages.disconnect_confirm_message') }}">
                                    <input class="btn btn-outline-danger d-inline float-right ml-2" type="submit" value="{{ __('messages.remove_request') }}">
                                </form>
                            @endif
                            {{-- if user is disconnected from therapist, he will be able to reconnect with him --}}
                            @if(
                                $request->status->status === \App\Models\Status::REMOVED
                                || $request->status->status === \App\Models\Status::REFUSED
                            )
                                <form class="form-inline d-inline" method="POST" action="{{ route('connect.therapist', $request->therapist_id) }}">
                                    @method('post')
                                    @csrf
                                    <input class="btn btn-outline-dark d-inline float-right" type="submit" value="{{ __('messages.reconnect_therapist') }}">
                                </form>
                            @endif
                        @endif
                        @if($request->type->type === \App\Models\RequestType::FEEDBACK)
                            @if($request->status->status === \App\Models\Status::INITIATED)
                                <form class="form-inline d-inline" method="POST" action="{{ route('patient.remove.request', $request->id) }}">
                                    @method('post')
                                    @csrf
                                    <input class="btn btn-outline-danger d-inline float-right" type="submit" value="{{ __('messages.remove_request') }}">
                                </form>
                            @else
                                <form class="form-inline d-inline" method="POST" action="{{ route('request.feedback', $request->therapist_id) }}">
                                    @method('post')
                                    @csrf
                                    <input class="btn btn-outline-dark d-inline float-right" type="submit" value="{{ __('messages.request_feedback_again') }}">
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
