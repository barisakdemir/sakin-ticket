@extends('app')

@section('content')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                    Dashboard
                </h2>
            </div>
            <!-- Page title actions -->
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <!-- ### -->
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Active Ticket</div>
                        </div>
                        <div class="h1 mb-3">{{ $data['active_ticket_count'] }}</div>
                    </div>
                </div>
            </div>

            <!-- ### -->
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Ticket</div>
                            <div class="ms-auto lh-1">
                                <div class="dropdown">
                                    <span class="text-muted" aria-haspopup="true" aria-expanded="false">Last 7 days</span>
                                </div>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $data['ticket_count_last_7days'] }}</div>
                    </div>
                </div>
            </div>

            <!-- ### -->
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Message</div>
                            <div class="ms-auto lh-1">
                                <div class="dropdown">
                                    <span class="text-muted" aria-haspopup="true" aria-expanded="false">Last 7 days</span>
                                </div>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $data['message_count_last_7days'] }}</div>
                    </div>
                </div>
            </div>

        </div>

        <hr />

    </div>
</div>
@endsection
