@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($therapist))
            <h3 class="mb-3">{{ __('messages.therapist_info') }}</h3>
            <div class="row">
                <div class="col-6 col-md-4">
                    <img class="img-fluid img-thumbnail mb-3" src="{{ url('images/' . $therapist->profile_picture) }}" alt="{{ __('messages.profile_picture') }}"/>
                </div>
                <div class="col-12 col-md-8">
                    <h4>{{ $therapist->user->name }} {{ $therapist->user->surname }}</h4>
                    @if($therapist->date_of_birth)
                        <b>{{ __('messages.date_of_birth') }}:</b>
                        {{ DateTime::createFromFormat('Y-m-d', $therapist->date_of_birth)->format('F j, Y')  }}
                        <br>
                    @endif
                    <b>{{ __('messages.gender') }}:</b>
                    @if(app()->getLocale() === 'en')
                        {{ $therapist->gender->gender }}
                    @endif
                    @if(app()->getLocale() === 'lv')
                        {{ $therapist->gender->gender_lv }}
                    @endif
                    <br class="mb-2">
                    <b>{{ __('messages.specialization') }}:</b>
                    <p>
                        {{ $therapist->specialization }}
                    </p>
                    <hr>
                    <h5>{{ __('messages.education_info') }}</h5>
                    <p>
                        {{ $therapist->education_information }}
                    </p>
                    <h5>{{ __('messages.education_document') }}</h5>
                    <a href="{{ url('documents/' . $therapist->education_document) }}" target="_blank">{{ __('messages.document_see') }}</a>
                    @if($therapist->additional_information)
                        <div class="mt-2">
                            <h5>{{ __('messages.additional_information') }}</h5>
                            <p>
                                {{ $therapist->additional_information }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            @if(Auth::id() === $therapist->user->id)
                <hr>
                <div class="btn-group" role="group">
                    <a class="btn btn-outline-dark" href="/therapist/edit">{{ __('messages.edit') }}</a>
                </div>
                <div class="btn-group ml-2" role="group">
                    <form method="POST" onsubmit="return confirm(document.getElementById('confirm_message').value.toString());" action="{{ route('therapist.remove', $therapist->id) }}">
                        @csrf
                        @method('post')
                        <input type="hidden" id="confirm_message" value="{{ __('messages.info_remove_confirm') }}">
                        <input class="btn btn-outline-danger" type="submit" value="{{ __('messages.info_remove') }}">
                    </form>
                </div>
            @endif
            <hr>
            <h4 class="d-inline">{{ __('messages.reviews') }}</h4>
            @if(!empty($reviews) && count($reviews))
                <h5 class="d-inline float-right"><b>{{ __('messages.rating') }}: </b> {{ $rating }}</h5>
                <br class="mb-3">
                @foreach($reviews as $review)
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <h5 class="card-title">
                                {{ __('messages.author') }}:
                                {{ $review->patient->user->name }} {{ $review->patient->user->surname }}
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                {{ DateTime::createFromFormat('Y-m-d', $review->date)->format('F j, Y')  }}
                            </h6>
                            <p class="card-text">
                                {{ $review->text }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info w-50 mt-2">
                    {{ __('messages.no_reviews') }}
                </div>
            @endif
        @else
            <div class="alert alert-info w-50">
                {{ __('messages.no_info') }}
            </div>
        @endif
    </div>
@endsection
