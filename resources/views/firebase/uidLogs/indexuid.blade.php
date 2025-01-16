{{--myproject\resources\views\firebase\uidLogs\indexuid.blade.php--}}

@extends('firebase.app')

@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>RFID Logs
                        <a href="{{ url('/') }}" class="btn btn-sm btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>UID</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rfidLogs)
                                @foreach ($rfidLogs as $key => $log)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $log['uid'] ?? 'N/A' }}</td>
                                        <td>{{ $log['timestamp'] ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center">No RFID Logs Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
