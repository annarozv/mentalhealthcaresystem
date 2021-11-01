@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('patient.store') }}" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="form-group">
                <label for="profile_picture">{{ __('messages.profile_picture') }}</label>
                <input class="form-control w-auto @error('profile_picture') is-invalid @enderror" type="file" id="profile_picture" name="profile_picture">
                @error('profile_picture')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="date_of_birth">{{ __('messages.date_of_birth') }}</label>
                <input class="form-control w-auto @error('date_of_birth') is-invalid @enderror" type="date" id="date_of_birth" name="date_of_birth">
                @error('date_of_birth')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="gender">{{ __('messages.gender') }}</label>
                <select class="form-control w-auto @error('gender') is-invalid @enderror" id="gender" name="gender">
                    @foreach($genders as $gender)
                        <option value="{{ $gender->id }}" @if($gender->id === 4) selected @endif>
                            @if (app()->getLocale() === 'en')
                                {{ $gender->gender }}
                            @endif
                            @if (app()->getLocale() === 'lv')
                                {{ $gender->gender_lv }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="date_of_birth">{{ __('messages.additional_information') }}</label>
                <textarea class="form-control w-100 @error('additional_information') is-invalid @enderror" id="additional_information" name="additional_information"></textarea>
                @error('additional_information')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <input class="btn btn-dark w-100" type="submit" value="{{ __('messages.save_info') }}">
        </form>
    </div>
@endsection
