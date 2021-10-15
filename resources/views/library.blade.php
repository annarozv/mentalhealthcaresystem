@extends('layouts.app')

@section('content')
        <div class="d-flex justify-content-center">
            <a class="btn btn-dark ml-1" href="{{ route('illness.all') }}">{{ __('messages.illnesses_title')  }}</a>
            <a class="btn btn-dark ml-2 mr-1" href="{{ route('symptom.all') }}">{{ __('messages.symptoms_title')  }}</a>
        </div>
        <div class="d-flex justify-content-center mt-4">
            <img class="img-fluid" src="{{  URL::to('/assets/img/pines.jpg') }}"/>
        </div>
@endsection
