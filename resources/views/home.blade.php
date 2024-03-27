@extends('layouts.app')

@section('content')
    <script>
        window.user_id = {{ auth()->check() ? auth()->user()->id : 'null' }}
    </script>
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @include('createPostForm')
                <div id="spa">
                    {{-- <posts-index :user_id={{ auth()->user()->id }}></posts-index> --}}
                </div>

                {{-- <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
