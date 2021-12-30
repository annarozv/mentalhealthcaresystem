@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 mx-auto">
                <h4 class="mb-4">
                    {{ __('messages.update_symptom') }}
                </h4>
                @if(!empty($symptom))
                    <form method="POST" action="{{ route('symptom.update', $symptom->id) }}">
                        @csrf
                        @method('post')
                        <div class="form-group">
                            <label class="control-label p-0" for="symptom_name">{{ __('messages.symptom_name') }}</label>
                            <input class="form-control @error('symptom_name') is-invalid @enderror" id="symptom_name" name="symptom_name" type="text" value="{{ $symptom->symptom_name }}">
                            @error('symptom_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="control-label p-0" for="latvian_symptom_name">{{ __('messages.symptom_name_lv') }}</label>
                            <input class="form-control @error('latvian_symptom_name') is-invalid @enderror" id="latvian_symptom_name" name="latvian_symptom_name" type="text" value="{{ $symptom->symptom_name_lv }}">
                            @error('latvian_symptom_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="control-label p-0" for="description">{{ __('messages.symptom_desc') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" type="text">{{ $symptom->description }}</textarea>
                            @error('description')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="control-label p-0" for="latvian_description">{{ __('messages.symptom_desc_lv') }}</label>
                            <textarea class="form-control @error('latvian_description') is-invalid @enderror" id="latvian_description" name="latvian_description" type="text">{{ $symptom->description_lv }}</textarea>
                            @error('latvian_description')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="btn btn-dark mt-2 w-100" type="submit" value="{{ __('messages.update_symptom') }}">
                        </div>
                    </form>
                @else
                    <div class="alert alert-info">
                        {{ __('messages.no_info') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
