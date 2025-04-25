@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Create User</h1>
    </div>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        @include('users.form')

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
