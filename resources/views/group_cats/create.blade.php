@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Create Group Category</h2>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('group_cats.index') }}" class="btn btn-primary mb-3">Back to List</a>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Error!</strong> Please check the form fields.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('group_cats.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name">Name:</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" value="{{ old('name') }}">
                            </div>

                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
