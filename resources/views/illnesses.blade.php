@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!Auth::guest() && Auth::user()->isAdmin())
            <a class="btn btn-outline-dark" href="/illness/create">
                {{ __('messages.create_illness') }}
            </a>
        @endif
        <form method="POST" class="form-horizontal" action="{{ route('illnesses.filter') }}">
            @csrf
            @method('post')
            <div class="form-row justify-content-end">
                <input class="form-control w-auto" id="keyword"
                       name="keyword" type="text" maxlength="30"
                       placeholder="{{ __('messages.enter_keyword') }}">
                <input class="btn btn-dark ml-2" type="submit"
                       value="{{ __('messages.search') }}">
            </div>
        </form>
        <hr>
        <h3 class="text-center">{{ __('messages.illnesses_title') }}</h3>
        <hr>
        <div class="d-flex justify-content-center">
            <div class="w-lg-50">
                @if(!empty($illnesses) && count($illnesses))
                    @foreach($illnesses as $illness)
                            <div class="border border-secondary text-center rounded p-2 pl-3 pr-3 pt-3 mb-2">
                                <h5>
                                    <a href="{{ route('illness.details', $illness->id)  }}"
                                       class="text-dark">
                                        @if (app()->getLocale() === 'en')
                                            {{ $illness->illness_name  }}
                                        @endif
                                        @if (app()->getLocale() === 'lv')
                                            {{ $illness->illness_name_lv  }}
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
