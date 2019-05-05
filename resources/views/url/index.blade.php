@extends('layouts.default')

@section('content')
    <div class="col-12">
        <h1 class="mb-5">Laravel Link Shortener</h1>
        @if (session()->has('shortUrl'))
            <div class="alert alert-success" role="alert">
                Your short url is <a href="{{ session('shortUrl') }}">{{ session('shortUrl') }}</a>
            </div>
        @endif
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
        <form method="POST" action="{{ route('url.add') }}">
            @csrf
            <div class="input-group">
                <input type="text"
                       class="form-control form-control-lg {{ $errors->has('url') ? 'is-invalid' : '' }}"
                       name="UrlForm[url]"
                       placeholder="http://example.com"/>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Get short link!</button>
                </div>
            </div>
        </form>

        @if ($linksPagination->count())
            <h1 class="mb-5 urls__table-title">Recent short links</h1>
            <div class="table-responsive">
                <table class="table table-striped urls__table">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Url</th>
                        <th scope="col">Short url</th>
                        <th scope="col">Counter</th>
                        <th scope="col">Created</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($linksPagination->items() as $linkItem)
                        <tr>
                            <th>{{ $linkItem->id }}</th>
                            <td>
                                <a href="{{ $linkItem->url }}" target="_blank">{{ $linkItem->url }}</a>
                            </td>
                            <td>
                                <a href="{{ $linkItem->shortUrl }}" target="_blank">{{ $linkItem->shortUrl }}</a>
                            </td>
                            <td>{{ $linkItem->counter }}</td>
                            <td>{{ $linkItem->createdAtFormat }}</td>
                            <td>
                                <a href="{{ route('url.remove', ['url_id' => $linkItem->id]) }}">Remove</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection