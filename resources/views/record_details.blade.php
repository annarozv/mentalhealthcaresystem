@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($record))
            <h5 class="d-inline">
                {{ __('messages.author') }}:
                @if(Auth::check() && $record->author_id === Auth::id())
                    {{ __('messages.me') }}
                @else
                    {{ $record->author->name }} {{ $record->author->surname }}
                @endif
            </h5>
            <h5 class="text-muted d-inline float-right">
                {{ __('messages.state') }}:
                @if(app()->getLocale() === 'en')
                    {{ $record->state->state }}
                @endif
                @if(app()->getLocale() === 'lv')
                    {{ $record->state->state_lv }}
                @endif
            </h5>
            <hr>
            <h5 class="text-dark">
                {{ DateTime::createFromFormat('Y-m-d', $record->date)->format('F j, Y')  }}
            </h5>
            <p style="white-space: pre-wrap;">{{ $record->record_text }}</p>
            <hr>
            @if(Auth::check() && $record->author_id === Auth::id())
                <a class="btn btn-outline-info" href="/record/{{ $record->id }}/edit">{{ __('messages.edit_text') }}</a>
                <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('record_delete_confirm').value.toString());" action="{{ route('record.delete', $record->id) }}">
                    @method('post')
                    @csrf
                    <input type="hidden" id="record_delete_confirm" value="{{ __('messages.record_delete_confirm') }}">
                    <input class="btn btn-outline-danger d-inline" type="submit" value="{{ __('messages.remove') }}">
                </form>
                <hr>
            @endif
            @if(!empty($symptoms) && count($symptoms))
                <div class="card w-75">
                    <div class="card-header">
                        {{ __('messages.connected_symptoms') }}
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($symptoms as $symptom)
                            <li class="list-group-item">
                                <a href="{{ route('symptom.details', $symptom->id) }}" class="text-dark">
                                    @if(app()->getLocale() === 'en')
                                        {{ $symptom->symptom_name }}
                                    @endif
                                    @if(app()->getLocale() === 'lv')
                                        {{ $symptom->symptom_name_lv }}
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="alert alert-info">
                    {{ __('messages.no_symptoms_connected') }}
                </div>
            @endif
            <hr>
            <div class="pb-2">
                <h4 class="d-inline">{{ __('messages.comments') }}</h4>
                <a class="btn btn-outline-dark d-inline float-right" href="/comment/{{ $record->id }}/new">{{ __('messages.new_comment') }}</a>
            </div>
            <hr>
            @if(!empty($comments) && count($comments))
                @foreach($comments as $comment)
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <h5 class="card-title d-inline">
                                {{ __('messages.author') }}:
                                @if(Auth::check() && $comment->author_id === Auth::id())
                                    {{ __('messages.me') }}
                                @else
                                {{ $comment->author->name }} {{ $comment->author->surname }}
                                @endif
                            </h5>
                            <p class="card-text">
                                {{ $comment->comment_text }}
                            </p>
                            @if(Auth::check() && (Auth::user()->isModerator() || (Auth::id() === $comment->author_id)))
                                <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('comment_delete_confirm').value.toString());" action="{{ route('comment.delete', $comment->id) }}">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" id="comment_delete_confirm" value="{{ __('messages.comment_delete_confirm') }}">
                                    <input class="btn btn-outline-danger d-inline float-right" type="submit" value="{{ __('messages.remove') }}">
                                </form>
                            @endif
                            @if(Auth::check() && Auth::id() === $comment->author_id)
                                <a class="btn btn-outline-info d-inline float-right mr-2" href="/comment/{{ $comment->id }}/edit">{{ __('messages.edit_comment') }}</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    {{ __('messages.no_comments') }}
                </div>
            @endif
        @else
            <div class="alert alert-info">
                {{ __('messages.no_info') }}
            </div>
        @endif
    </div>
@endsection
