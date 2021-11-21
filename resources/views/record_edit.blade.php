@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($record))
            <form method="POST" action="{{ route('record.update', $record->id) }}">
                @method('post')
                @csrf
                <div class="form-group">
                    <label for="record_text">{{ __('messages.describe_state') }}</label>
                    <textarea class="form-control @error('record_text') is-invalid @enderror" id="record_text" name="record_text">{{ $record->record_text }}</textarea>
                    @error('record_text')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input class="btn btn-dark" type="submit" value="{{ __('messages.save_record') }}">
                </div>
            </form>
        @else
            <div class="alert alert-info">
                {{ __('messages.no_info') }}
            </div>
        @endif
    </div>
@endsection
