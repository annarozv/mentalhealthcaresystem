@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>{{ __('auth.profile_info') }}</h4>
        <hr>
        <table class="w-100">
            <tr>
                <td>
                    <b>{{ __('auth.role') }}:</b>
                </td>
                <td>
                    @if (app()->getLocale() === 'en')
                        {{ $user->role->role }}
                    @endif
                    @if (app()->getLocale() === 'lv')
                        {{ $user->role->role_lv }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <b>{{ __('auth.name') }}: </b>
                </td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td>
                    <b>{{ __('auth.surname') }}: </b>
                </td>
                <td>{{ $user->surname }}</td>
            </tr>
            <tr>
                <td class="w-25">
                    <b>{{ __('auth.email') }}: </b>
                </td>
                <td>{{ $user->email }}</td>
            </tr>
        </table>
        <hr>
        <div class="btn-toolbar" role="toolbar">
            @if(Auth::user()->isPatient())
                <div class="btn-group" role="group">
                    {{-- if patient user has patient information connected with his profile, he will be able to see it --}}
                    @if($user->patient && $user->patient->is_active)
                        <a class="btn btn-outline-dark" href="/patient/{{ $user->patient->id }}/info">{{ __('auth.public_info') }}</a>
                        {{-- otherwise he will be offered to create public patient information --}}
                    @else
                        <a class="btn btn-outline-dark" href="/patient/new">{{ __('auth.create_public_info') }}</a>
                    @endif
                </div>
            @endif
            @if(Auth::user()->isTherapist())
                <div class="btn-group" role="group">
                    {{-- if therapist user has therapist information connected with his profile, he will be able to see it --}}
                    @if($user->therapist && $user->therapist->is_active)
                        <a class="btn btn-outline-dark" href="/therapist/{{ $user->therapist->id }}/info">{{ __('auth.public_info') }}</a>
                        {{-- otherwise he will be offered to create public therapist information --}}
                    @else
                        <a class="btn btn-outline-dark" href="/therapist/new">{{ __('auth.create_public_info') }}</a>
                    @endif
                </div>
            @endif
            <div class="btn-group ml-2" role="group">
                <form method="POST" onsubmit="return confirm(document.getElementById('confirm_message').value.toString());" action="{{ route('user.deactivate', Auth::id()) }}">
                    @csrf
                    @method('post')
                    <input type="hidden" id="confirm_message" value="{{ __('auth.profile_deactivation_confirm') }}">
                    <input class="btn btn-outline-danger" type="submit" value="{{ __('auth.profile_deactivate') }}">
                </form>
            </div>
        </div>
    </div>
@endsection
