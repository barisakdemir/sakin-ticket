@extends('app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        {{ $ticket->title }} / {{ $ticket->department->name }} / {{ $ticket->importance }}
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

                    <form action="{{ route('agent.ticket.message.store', ['id' => $ticket->id]) }}"method="POST" class="card">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="col-md-6 col-xl-12">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.message') }} <span class="form-label-description">56/100</span></label>
                                            <textarea class="form-control" name="message" rows="6" placeholder="Message.."></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-success ms-auto"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> {{ __('messages.post') }}</button>
                            </div>
                        </div>
                    </form>
                </div>

                @foreach($ticket->ticketMessage as $ticketMessage)
                    <div class="col-12">
                        <div class="row">
                            @if(Auth::user()->id === $ticketMessage->user->id)
                                <div class="col-xl-3">&nbsp;</div>
                                <div class="col-xl-9 card border rounded-3 border-success">
                            @else
                                <div class="col-xl-9 card border rounded-3 border-secondary">
                            @endif
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="col-md-6 col-xl-12">
                                                <div class="mb-3">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h2 class="page-title">{{ $ticketMessage->user->name }}</h2>
                                                            <div class="page-subtitle">
                                                                <div class="row">
                                                                    <div class="col-auto">
                                                                        <!-- Download SVG icon from http://tabler-icons.io/i/building-skyscraper -->
                                                                        <!-- SVG icon code -->
                                                                        <span class="text-reset">{{ $ticketMessage->created_at }}</span> -
                                                                        <span href="#" class="text-reset">{{ Carbon\Carbon::parse($ticketMessage->created_at)->diffForHumans()}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr style="margin: 1rem 0;" />
                                                    <pre>{{ $ticketMessage->message }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!--<div class="col-12">
                    <div class="row">
                        <div class="col-xl-9 card border rounded-3 border-secondary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="col-md-6 col-xl-12">
                                            <div class="mb-3">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h2 class="page-title">Lorem Ipsum</h2>
                                                        <div class="page-subtitle">
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <a href="#" class="text-reset">2021/03/09 12:30</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <p>
                                                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">&nbsp;</div>
                    </div>
                </div>-->

            </div>
        </div>
    </div>
@endsection
