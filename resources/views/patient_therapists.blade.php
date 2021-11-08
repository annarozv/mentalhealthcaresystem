@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($therapists) && count($therapists))
            {{-- TODO: display list of therapists --}}
        @else
            <div class="alert alert-info w-75">
                {{ __('messages.no_therapists') }}
            </div>
            <a class="btn btn-outline-dark" href="">{{ __('messages.find_therapist') }}</a>
        @endif
    </div>
@endsection
