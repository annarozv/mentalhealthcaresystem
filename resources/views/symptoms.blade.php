@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!Auth::guest() && Auth::user()->isAdmin())
            <a class="btn btn-outline-dark" href="/symptom/create">{{ __('messages.create_symptom') }}</a>
        @endif
        <form method="POST" class="form-horizontal" action="{{ route('symptoms.filter') }}">
            @csrf
            @method('post')
            <div class="form-row justify-content-end">
                <input class="form-control w-25" id="keyword" name="keyword" type="text" maxlength="30" placeholder="{{ __('messages.enter_keyword') }}">
                <input class="btn btn-dark ml-2" type="submit" value="{{ __('messages.search') }}">
            </div>
        </form>
        <hr>
        <h3 class="text-center">{{ __('messages.symptoms_title') }}</h3>
        <hr>
        <div class="d-flex justify-content-center">
            <div class="w-lg-50">
                @if(!empty($symptoms) && count($symptoms))
                    @foreach($symptoms as $symptom)
                            <div class="border border-secondary text-center rounded p-2 pr-3 pl-3 pt-3 mb-2">
                                <h5>
                                    <a href="{{ route('symptom.details', $symptom->id)  }}" style="color: #17a2b8 !important">
                                        @if (app()->getLocale() === 'en')
                                            {{ $symptom->symptom_name  }}
                                        @endif
                                        @if (app()->getLocale() === 'lv')
                                            {{ $symptom->symptom_name_lv  }}
                                        @endif
                                    </a>
                                </h5>
                            </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        {{ __('messages.no_records') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
