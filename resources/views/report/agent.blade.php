@extends('app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        {{ __('messages.agent_report') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-12">
                    @if(count($errors) > 0)
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $error)
                                <div class="text-muted">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('admin.report.agent') }}" method="GET" class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-3">
                                    <div class="row">
                                        <div class="col-md-6 col-xl-12">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.start_date') }}</label>
                                                <div class="input-icon mb-2">
                                                    <input class="form-control " placeholder="Select a date"
                                                           name="start_date" id="start_date" value="{{ $form['start_date'] }}"/>
                                                    <span class="input-icon-addon"><!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                             height="24" viewBox="0 0 24 24" stroke-width="2"
                                                             stroke="currentColor" fill="none" stroke-linecap="round"
                                                             stroke-linejoin="round"><path stroke="none"
                                                                                           d="M0 0h24v24H0z"
                                                                                           fill="none"/><rect x="4"
                                                                                                              y="5"
                                                                                                              width="16"
                                                                                                              height="16"
                                                                                                              rx="2"/><line
                                                                x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3"
                                                                                                     x2="8" y2="7"/><line
                                                                x1="4" y1="11" x2="20" y2="11"/><line x1="11" y1="15"
                                                                                                      x2="12" y2="15"/><line
                                                                x1="12" y1="15" x2="12" y2="18"/></svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="row">
                                        <div class="col-md-6 col-xl-12">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.finish_date') }}</label>
                                                <div class="input-icon mb-2">
                                                    <input class="form-control" placeholder="Select a date"
                                                           name="finish_date" id="finish_date" value="{{ $form['finish_date'] }}"/>
                                                    <span class="input-icon-addon"><!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                             height="24" viewBox="0 0 24 24" stroke-width="2"
                                                             stroke="currentColor" fill="none" stroke-linecap="round"
                                                             stroke-linejoin="round"><path stroke="none"
                                                                                           d="M0 0h24v24H0z"
                                                                                           fill="none"/><rect x="4"
                                                                                                              y="5"
                                                                                                              width="16"
                                                                                                              height="16"
                                                                                                              rx="2"/><line
                                                                x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3"
                                                                                                     x2="8" y2="7"/><line
                                                                x1="4" y1="11" x2="20" y2="11"/><line x1="11" y1="15"
                                                                                                      x2="12" y2="15"/><line
                                                                x1="12" y1="15" x2="12" y2="18"/></svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="row">
                                        <div class="col-md-6 col-xl-12">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('messages.agent') }}</div>
                                                <select class="form-select" name="agent" required>
                                                    <option value="all">All</option>
                                                    @foreach($agents as $agent)
                                                    <option value="{{ $agent->id }}" {{ ( $agent->id == $form['agent']) ? 'selected' : '' }}>{{ $agent->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-success ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calculator" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                                        <rect x="8" y="7" width="8" height="3" rx="1"></rect>
                                        <line x1="8" y1="14" x2="8" y2="14.01"></line>
                                        <line x1="12" y1="14" x2="12" y2="14.01"></line>
                                        <line x1="16" y1="14" x2="16" y2="14.01"></line>
                                        <line x1="8" y1="17" x2="8" y2="17.01"></line>
                                        <line x1="12" y1="17" x2="12" y2="17.01"></line>
                                        <line x1="16" y1="17" x2="16" y2="17.01"></line>
                                    </svg>
                                    {{ __('messages.submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if(isset($reportData))
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table
                                class="table table-vcenter card-table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.ticket') }}</th>
                                    <th>{{ __('messages.active') }}</th>
                                    <th>{{ __('messages.closed') }}</th>
                                    <th>{{ __('messages.ratio') }}</th>
                                    <th>{{ __('messages.message') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reportData as $data)
                                <tr>
                                    <td>{{ $data['date'] }}</td>
                                    <td>{{ $data['ticket_count'] }}</td>
                                    <td>{{ $data['active_ticket_count'] }}</td>
                                    <td>{{ $data['closed_ticket_count'] }}</td>
                                    <td>%{{ $data['ratio_ticket_count'] }}</td>
                                    <td>{{ $data['message_count'] }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
