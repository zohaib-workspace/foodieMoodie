@extends('layouts.admin.app')

@section('title', translate('User Support Center'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{ translate('messages.User') }}
                        {{ translate('messages.Quires') }} </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">

       
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ translate('messages.Query') }} {{ translate('messages.Reports') }}<span
                                class="badge badge-soft-dark ml-2" id="itemCount">{{ $orderReports->count() }}</span></h5>
                       
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                "order": [],
                                "orderCellsTop": true,
                                "search": "#datatableSearch",
                                "entries": "#datatableEntries",
                                "isResponsive": false,
                                "isShowPaging": false,
                                "paging": false,
                               }'>
                            <thead class="thead-light ">
                                <tr>
                                    <th>{{ translate('messages.sl') }}</th>
                                    <th>{{ translate('messages.User Name') }}</th>
                                    <th>{{ translate('messages.query') }}</th>
                                    <th>{{ translate('messages.query_description') }}</th>
                                    <th>{{ translate('messages.response') }}</th>
                                    <th class="text-center">{{ translate('messages.status') }}</th>
                                    <th class="text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($orderReports as $key => $orderRepo)
                                    <tr>
                                        <td>
                                            {{ $key + $orderReports->firstItem() }}

                                        </td>
                                        <td>
                                            {{$orderRepo['user']['f_name']}}  {{$orderRepo['user']['l_name']}}
                                        </td>
                                        <td>{{ translate('messages.' . $orderRepo['name']) }}</td>
                                        <td>{{ translate('messages.' . $orderRepo['description']) }}</td>
                                        <td>{{ translate('messages.' . $orderRepo['response']) }}</td>  
                                        <td class="text-capitalize text-center">
                                            <span
                                                class=" badge 
                                                    {{ $orderRepo['status'] == 'completed' ? 'badge-info' : ($orderRepo['status'] == 'pending' ? 'badge-warning' : ($orderRepo['status'] == 'rejected' ? 'badge-danger' : '')) }}
                                                    ">
                                                {{ $orderRepo['status'] }}
                                            </span>
                    </div>
                    </td>
                    <td>
                        <div class="btn--container justify-content-center" data-toggle="modal"
                            data-target="#exampleModal{{ $orderRepo['id'] }}">
                            <a class="ml-2 btn btn-sm btn--warning btn-outline-warning action-btn">
                                <i class="tio-edit"></i>
                            </a>


                        </div>
                     
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{ $orderRepo['id'] }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.User Query') }}
                                            {{$orderRepo['user']['f_name']}} {{ $orderRepo['user']['l_name']}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.userquires.admin-response', $orderRepo->id) }}" method="post" id="banner_for">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="input-label"
                                                            for="exampleFormControlInput1">{{ translate('messages.title') }}</label>
                                                        <textarea name="response" class="form-control" placeholder="{{ translate('messages.Response_to_the_Report') }}" >{{ translate('messages.' . $orderRepo['response']) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="input-label"
                                                            for="exampleFormControlInput1">{{ translate('messages.status') }}</label>
                                                    <select name="status" id="priority1"
                                                        class=" form-control form--control-select {{ $orderRepo->status == 'pending' ? 'text--title' : '' }} {{ $orderRepo->status == 'completed' ? 'text--info' : '' }} {{ $orderRepo->status == 'rejected' ? 'text--danger' : '' }} "
                                                        >
                                                        <option class="text--title" value="pending"
                                                            {{ $orderRepo->status == 'pending' ? 'selected' : '' }}>
                                                            {{ translate('messages.Pending') }}</option>
                                                        <option class="text--info" value="completed"
                                                            {{ $orderRepo->status == 'completed' ? 'selected' : '' }}>
                                                            {{ translate('messages.Completed') }}</option>
                                                        <option class="text--danger" value="rejected"
                                                            {{ $orderRepo->status == 'rejected' ? 'selected' : '' }}>
                                                            {{ translate('messages.Rejected') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="btn--container justify-content-end">
                                                {{-- <button id="reset_btn" type="reset"
                                                    class="btn btn--reset">{{ translate('messages.reset') }}</button> --}}
                                                <button type="submit"
                                                    class="btn btn--primary">{{ translate('messages.submit') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    {{-- <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>
                    @if (count($orderReports) === 0)
                        <div class="empty--data">
                            <img src="{{ asset('/public/assets/admin/img/empty.png') }}" alt="public">
                            <h5>
                                {{ translate('no_data_found') }}
                            </h5>
                        </div>
                    @endif
                    <div class="page-area px-4 pb-3">
                        <div class="d-flex align-items-center justify-content-end">
                            {{-- <div>
                                    1-15 of 380
                                </div> --}}
                            <div>
                                {!! $orderReports->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Table -->
    </div>
    </div>

@endsection

