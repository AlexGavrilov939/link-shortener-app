@extends('layouts.default')

@section('content')
    <div class="col-12">
        <h1 class="text-center mb-5">Laravel Link Shortener</h1>
        <form method="POST" action="{{ route('url.store') }}">
            @csrf
            <div class="input-group">
                <input type="text"
                       class="form-control form-control-lg {{ $errors->has('url') ? 'is-invalid' : '' }}"
                       id="url"
                       name="url"
                       placeholder="http://example.com"
                       value="{{ old('url') }}"/>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Short it!</button>
                </div>
            </div>
            @if ($errors->has('url'))
                <small id="url-error" class="form-text text-danger">
                    {{ $errors->first('url') }}
                </small>
            @endif
        </form>
    </div>
@endsection