@extends('app')

@section('content')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                    {{ __('messages.dashboard') }}
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
                            <div class="subheader">{{ __('messages.active_ticket') }}</div>
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
                            <div class="subheader">{{ __('messages.ticket') }}</div>
                            <div class="ms-auto lh-1">
                                <div class="dropdown">
                                    <span class="text-muted" aria-haspopup="true" aria-expanded="false">{{ __('messages.last_days', ['DAYS' => '7']) }}</span>
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
                            <div class="subheader">{{ __('messages.message') }}</div>
                            <div class="ms-auto lh-1">
                                <div class="dropdown">
                                    <span class="text-muted" aria-haspopup="true" aria-expanded="false">{{ __('messages.last_days', ['DAYS' => '7']) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $data['message_count_last_7days'] }}</div>
                    </div>
                </div>
            </div>

            <!-- ### -->
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">{{ __('messages.agent') }}</div>
                        </div>
                        <div class="h1 mb-3">{{ $data['agent_count'] }}</div>
                    </div>
                </div>
            </div>

            <!-- ### -->
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">{{ __('messages.customer') }}</div>
                        </div>
                        <div class="h1 mb-3">{{ $data['customer_count'] }}</div>
                    </div>
                </div>
            </div>

            <!-- ### -->
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">{{ __('messages.guest') }}</div>
                        </div>
                        <div class="h1 mb-3">{{ $data['guest_count'] }}</div>
                    </div>
                </div>
            </div>

        </div>

        <hr />

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <h3 class="card-title">{{ __('messages.tickets_and_messages') }}</h3>
                        <div class="ms-auto">
                            <div class="dropdown">
                                <span class="text-muted" aria-haspopup="true" aria-expanded="false">{{ __('messages.last_days', ['DAYS' => '30']) }}</span>
                            </div>
                        </div>
                    </div>
                    <div id="chart-social-referrals"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-social-referrals'), {
            chart: {
                type: "line",
                fontFamily: 'inherit',
                height: 288,
                parentHeightOffset: 0,
                toolbar: {
                    show: false,
                },
                animations: {
                    enabled: false
                },
            },
            fill: {
                opacity: 1,
            },
            stroke: {
                width: 2,
                lineCap: "round",
                curve: "smooth",
            },
            series: [{
                name: "{{ __('messages.messages') }}",
                data: [@php echo implode(',',  $data['last_30_days_ticket_message_counts']['data']) @endphp]
            },{
                name: "{{ __('messages.tickets') }}",
                data: [@php echo implode(',',  $data['last_30_days_ticket_counts']['data']) @endphp]
            }],
            grid: {
                padding: {
                    top: -20,
                    right: 0,
                    left: -4,
                    bottom: -4
                },
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                type: 'datetime',
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            labels: ['@php echo implode('\',\'',  $data['last_30_days_ticket_message_counts']['date']) @endphp'],
            colors: ["#3b5998", "#1da1f2"],
            legend: {
                show: true,
                position: 'bottom',
                offsetY: 12,
                markers: {
                    width: 10,
                    height: 10,
                    radius: 100,
                },
                itemMargin: {
                    horizontal: 8,
                    vertical: 8
                },
            },
        })).render();
    });
    // @formatter:on
</script>
@endsection
