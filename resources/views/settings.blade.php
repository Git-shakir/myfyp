{{--myproject\resources\views\settings.blade.php--}}

@extends('firebase.app')

@section('content')

<div class="container-fluid welcome-background">
    <div class="welcome-overlay"></div>
    <div class="row">
        <div class="col-md-12 welcome-content">

            @if(session('status'))
                <h4 class="alert alert-warning mb-2">{{session('status')}}</h4>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Settings
                        {{--<a href="{{ url('add-animalData') }}" class="btn btn-sm btn-primary float-end">Add Animal</a>--}}
                    </h4>
                </div>
                <div class="card-body">
                    ---
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
