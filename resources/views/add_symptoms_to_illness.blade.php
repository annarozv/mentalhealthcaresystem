@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>
            @if(app()->getLocale() === 'en')
                {{ $illness->illness_name }}
            @endif
            @if(app()->getLocale() === 'lv')
                {{ $illness->illness_name_lv }}
            @endif
        </h4>
        <hr>
        <form method="POST" action="{{ route('illness.add_symptoms', $illness->id) }}">
            @csrf
            @method('post')
            <label for="symptoms">{{ __('messages.symptoms') }}</label>
            <select class="form-control" id="symptoms" name="symptoms[]" multiple="multiple">
                @foreach($symptoms as $symptom)
                    <option value="{{ $symptom->id }}">
                        @if (app()->getLocale() === 'en')
                            {{ $symptom->symptom_name  }}
                        @endif
                        @if (app()->getLocale() === 'lv')
                            {{ $symptom->symptom_name_lv  }}
                        @endif
                    </option>
                @endforeach
            </select>
            <div class="form-group">
                <input class="btn btn-dark mt-2 w-100" type="submit" value="{{ __('messages.add_symptoms_to_illness') }}">
            </div>
        </form>
    </div>
@endsection
