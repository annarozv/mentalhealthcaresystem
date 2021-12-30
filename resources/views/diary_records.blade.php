@extends('layouts.app')

@section('content')
    <div class="container">
        <a class="btn btn-dark" href="/diary/{{ $patientId }}/new">{{ __('messages.new_diary_record') }}</a>
        @if(Auth::check() && Auth::user()->isPatient() && !empty($records) && count($records))
            <form class="form-inline d-inline" method="POST" onsubmit="return confirm(document.getElementById('all_records_delete_confirm').value.toString());" action="{{ route('records.delete.all', $patientId) }}">
                @method('post')
                @csrf
                <input type="hidden" id="all_records_delete_confirm" value="{{ __('messages.all_records_delete_confirm') }}">
                <input class="btn btn-outline-danger d-inline float-right" type="submit" value="{{ __('messages.remove_all_records') }}">
            </form>
        @endif
        <hr>
        @if(!empty($records) && count($records))
            @foreach($records as $record)
                <div class="card mb-2">
                    <a href="/record/{{ $record->id }}/info" class="text-decoration-none text-dark">
                        <div class="card-body p-3">
                            <h5 class="card-title d-inline">
                                {{ __('messages.author') }}:
                                @if(Auth::check() && $record->author_id === Auth::id())
                                    {{ __('messages.me') }}
                                @else
                                    {{ $record->author->name }} {{ $record->author->surname }}
                                @endif
                            </h5>
                            <h6 class="card-subtitle mb-2 mt-2 text-muted">
                                {{ DateTime::createFromFormat('Y-m-d', $record->date)->format('M j, Y')  }}
                            </h6>
                            <h6 class="text-muted">
                                {{ __('messages.state') }}:
                                @if(app()->getLocale() === 'en')
                                    {{ $record->state->state }}
                                @endif
                                @if(app()->getLocale() === 'lv')
                                    {{ $record->state->state_lv }}
                                @endif
                            </h6>
                            @php
                                $commentCount = 0;
                            @endphp
                            @foreach($record->comments as $comment)
                                @if((bool) $comment->is_active === true)
                                    @php $commentCount++ @endphp
                                @endif
                            @endforeach
                            <h6 class="text-muted">
                                {{ __('messages.comments') }}: {{ $commentCount }}
                            </h6>
                            <p class="card-text">
                                {{ $record->record_text }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                {{ __('messages.no_diary_records') }}
            </div>
        @endif
    </div>
@endsection
