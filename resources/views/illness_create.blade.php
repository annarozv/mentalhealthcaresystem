@extends('layouts.app')

@section('content')
    <div class="container m-1">
        <div class="row">
            <div class="col-sm-8 mx-auto">
                <h4 class="mb-4">
                    {{ __('messages.create_illness') }}
                </h4>
                <form method="POST" action="{{ route('illness.store') }}">
                    @csrf
                    @method('post')
                    <div class="form-group">
                        <label class="control-label p-0" for="illness_name">{{ __('messages.illness_name') }}</label>
                        <input class="form-control @error('illness_name') is-invalid @enderror" id="illness_name" name="illness_name" type="text">
                        @error('illness_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="control-label p-0" for="latvian_illness_name">{{ __('messages.illness_name_lv') }}</label>
                        <input class="form-control @error('latvian_illness_name') is-invalid @enderror" id="latvian_illness_name" name="latvian_illness_name" type="text">
                        @error('latvian_illness_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="control-label p-0" for="description">{{ __('messages.illness_desc') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" type="text"></textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="control-label p-0" for="latvian_description">{{ __('messages.illness_desc_lv') }}</label>
                        <textarea class="form-control @error('latvian_description') is-invalid @enderror" id="latvian_description" name="latvian_description" type="text"></textarea>
                        @error('latvian_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input class="btn btn-dark mt-2 w-100" type="submit" value="{{ __('messages.create_illness') }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
