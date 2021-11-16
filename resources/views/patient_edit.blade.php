@extends('layouts.app')

@section('content')
    <div class="container">
        @if(!empty($patient))
            <form method="POST" action="{{ route('patient.update', $patient->id) }}" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="form-group">
                    <label for="name">{{ __('auth.name') }}</label>
                    <input class="form-control w-50 @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ $patient->user->name }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="surname">{{ __('auth.surname') }}</label>
                    <input class="form-control w-50 @error('surname') is-invalid @enderror" type="text" id="surname" name="surname" value="{{ $patient->user->surname }}">
                    @error('surname')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @php
                    $hide_picture_input = false
                @endphp
                {{-- If user has a not default profile picture, he will be offered to remove his profile picture --}}
                @if ($patient->profile_picture !== Config::get('app.default_profile_picture_name'))
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remove_picture" name="remove_picture" onchange="
                            if (this.checked) {
                                document.getElementById('picture_block').style.display = 'block';
                            } else {
                                document.getElementById('picture_block').style.display = 'none';
                            }
                        ">
                        <label class="form-check-label" for="remove_picture">{{ __('messages.remove_profile_pic') }}</label>
                    </div>
                    @php
                        $hide_picture_input = true
                    @endphp
                @endif
                {{-- If user does not have any profile picture he will just be offered to upload one --}}
                <div class="form-group" id="picture_block" @if($hide_picture_input) style="display: none;" @endif>
                    <label for="profile_picture">{{ __('messages.new') }} {{ __('messages.profile_picture') }}</label>
                    <input class="form-control w-auto @error('profile_picture') is-invalid @enderror" type="file" id="profile_picture" name="profile_picture">
                    @error('profile_picture')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="date_of_birth">{{ __('messages.date_of_birth') }}</label>
                    <input class="form-control w-auto @error('date_of_birth') is-invalid @enderror" type="date" id="date_of_birth" name="date_of_birth" value="{{ $patient->date_of_birth }}">
                    @error('date_of_birth')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="gender">{{ __('messages.gender') }}</label>
                    <select class="form-control w-auto @error('gender') is-invalid @enderror" id="gender" name="gender">
                        @foreach($genders as $gender)
                            <option value="{{ $gender->id }}" @if($patient->gender_id === $gender->id) selected @endif>
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
                    <label for="additional_information">{{ __('messages.additional_information') }}</label>
                    <textarea class="form-control w-75 @error('additional_information') is-invalid @enderror" id="additional_information" name="additional_information">{{ $patient->additional_information }}</textarea>
                    @error('additional_information')
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
