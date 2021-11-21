@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('comment.update', $comment->id) }}">
            @method('post')
            @csrf
            <div class="form-group">
                <label for="comment_text">{{ __('messages.comment_text') }}</label>
                <textarea class="form-control @error('comment_text') is-invalid @enderror" id="comment_text" name="comment_text">{{ $comment->comment_text }}</textarea>
                @error('comment_text')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <input class="btn btn-dark mt-2 w-auto float-right" type="submit" value="{{ __('messages.save_comment') }}">
            </div>
        </form>
@endsection
