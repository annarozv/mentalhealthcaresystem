@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('record.store', $patientId) }}">
            @method('post')
            @csrf
            <div class="form-group">
                <label for="state">
                    @if(Auth::check() && Auth::user()->isPatient())
                        {{ __('messages.how_do_you_feel') }}
                    @else
                        {{ __('messages.how_does_patient_feel') }}
                    @endif
                </label>
                <select class="form-control" id="state" name="state">
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" @if($state->id === 3) selected @endif>
                            @if (app()->getLocale() === 'en')
                                {{ $state->state  }}
                            @endif
                            @if (app()->getLocale() === 'lv')
                                {{ $state->state_lv  }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="record_text">
                    @if(Auth::check() && Auth::user()->isPatient())
                        {{ __('messages.describe_state') }}
                    @else
                        {{ __('messages.describe_patient_state') }}
                    @endif
                </label>
                <textarea class="form-control @error('record_text') is-invalid @enderror" id="record_text" name="record_text">{{ old('record_text') }}</textarea>
                @error('record_text')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="symptoms">
                    @if(Auth::check() && Auth::user()->isPatient())
                        {{ __('messages.observed_symptoms') }}
                    @else
                        {{ __('messages.observed_patient_symptoms') }}
                    @endif
                </label>
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
            </div>
            <div class="form-group">
                <input class="btn btn-dark mt-2 w-auto float-right" type="submit" value="{{ __('messages.save_record') }}">
            </div>
        </form>
    </div>
@endsection
