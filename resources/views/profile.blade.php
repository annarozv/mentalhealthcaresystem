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
                        {{ $user->role->role_name }}
                    @endif
                    @if (app()->getLocale() === 'lv')
                        {{ $user->role->role_name_lv }}
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
        <form method="POST" onsubmit="return confirm(document.getElementById('confirm_message').value.toString());" action="{{ route('user.deactivate', Auth::id()) }}">
            @csrf
            @method('post')
            <input type="hidden" id="confirm_message" value="{{ __('auth.profile_deactivation_confirm') }}">
            <input class="btn btn-outline-danger" type="submit" value="{{ __('auth.profile_deactivate') }}">
        </form>
    </div>
@endsection
