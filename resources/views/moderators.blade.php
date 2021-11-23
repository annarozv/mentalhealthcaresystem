@extends('layouts.app')

@section('content')
    <div class="container">
        <a class="btn btn-outline-dark" href="/moderator/add">{{ __('messages.add_moderator') }}</a>
        <hr>
        @if(!empty($moderators) && count($moderators))
            @foreach($moderators as $moderator)
                <div class="card w-75 m-1">
                    <div class="card-body">
                        <h5 class="card-title">{{ $moderator->name }} {{ $moderator->surname }}</h5>
                        <h6 class="text-muted">{{ $moderator->email }}</h6>
                        <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('moderator_remove_confirm_message').value.toString());" action="{{ route('moderator.remove', $moderator->id) }}">
                            @method('post')
                            @csrf
                            <input type="hidden" id="moderator_remove_confirm_message" value="{{ __('messages.moderator_remove_confirm_message') }}">
                            <input class="btn btn-outline-danger d-inline float-right" type="submit" value="{{ __('messages.remove_moderator') }}">
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                {{ __('messages.no_moderators') }}
            </div>
        @endif
    </div>
@endsection
