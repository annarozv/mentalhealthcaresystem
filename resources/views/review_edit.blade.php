@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($review))
            <h4>{{ __('messages.review_edit') }}</h4>
            <hr>
            <form method="POST" action="{{ route('review.update', $review->id) }}">
                @method('post')
                @csrf
                <div class="form-group">
                    <label for="review_mark">{{ __('messages.review_mark') }} (0 - 10)</label>
                    <input class="form-control w-auto @error('review_mark') is-invalid @enderror" type="number" min="0" max="10" id="review_mark" name="review_mark" value="{{ $review->mark }}">
                    @error('review_mark')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="review_text">{{ __('messages.review_text') }}</label>
                    <textarea class="form-control w-75 @error('review_text') is-invalid @enderror" id="review_text" name="review_text">{{ $review->text }}
                    </textarea>
                    @error('review_text')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <input class="btn btn-dark w-75" type="submit" value="{{ __('messages.save_info') }}">
            </form>
        @else
            <div class="alert alert-info">
                {{ __('messages.no_info') }}
            </div>
        @endif
    </div>
@endsection
