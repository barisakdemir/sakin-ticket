@extends('app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        {{ $department->name }} {{ __('messages.department_agents') }}
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

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('admin.department.agent.store',['department_id'=>$department->id]) }}"
                          method="POST" class="card">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-xl-4">
                                        <div class="row">
                                            <div class="col-md-6 col-xl-12">
                                                <div class="mb-3">
                                                    <div class="form-label">{{ __('messages.agent') }}</div>
                                                    <select class="form-select" name="user_id">
                                                        <option value="">{{ __('messages.select') }}</option>
                                                        @foreach($agents as $agent)
                                                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
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
                                    <button type="submit" class="btn btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="icon icon-tabler icon-tabler-plus" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        {{ __('messages.add') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table
                                class="table table-vcenter card-table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.name') }}</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($departmentAgents as $departmentAgent)
                                    <tr>
                                        <td>{{ $departmentAgent->user->name }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <form
                                                    action="{{ route('admin.department.agent.delete',
                                                        ['department_id' => $departmentAgent->department_id, 'user_id' => $departmentAgent->user_id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-danger ms-auto"
                                                            onclick="return confirm('{{ __('messages.are_you_sure_you_want_to_delete_this') }}');">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="icon icon-tabler icon-tabler-edit" width="24"
                                                             height="24" viewBox="0 0 24 24" stroke-width="2"
                                                             stroke="currentColor" fill="none" stroke-linecap="round"
                                                             stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                                            <path
                                                                d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                                            <line x1="16" y1="5" x2="19" y2="8"></line>
                                                        </svg>
                                                        {{ __('messages.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
