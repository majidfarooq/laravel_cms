@extends('backend.layouts.app')

@section('style')

    <style type="text/css">

        td.action {
            display: flex;
            gap: 10px;
        }

        [data-title]:hover:after {
            opacity: 1;
            transition: all 0.1s ease 0.5s;
            visibility: visible;
        }

        [data-title]:after {
            content: attr(data-title);
            background-color: #f8f9fc;
            color: #4e73df;
            font-size: 15px;
            position: absolute;
            padding: 1px 5px 2px 5px;
            top: -35px;
            left: 0;
            white-space: nowrap;
            box-shadow: 1px 1px 3px #4e73df;
            opacity: 0;
            border: 1px solid #4e73df;
            z-index: 99999;
            visibility: hidden;
        }

        [data-title] {
            position: relative;
        }

    </style>

@endsection

@section('content')

    <div class="row">

        <div class="col-12">

            <div class="card border-0">

                @if ($message = \Illuminate\Support\Facades\Session::get('error'))

                    <div class="alert alert-danger">

                        <button type="button" class="close" data-dismiss="alert">×</button>

                        <strong>{{ $message }}</strong>

                    </div>

                @elseif ($message = \Illuminate\Support\Facades\Session::get('success'))

                    <div class="alert alert-success">

                        <button type="button" class="close" data-dismiss="alert">×</button>

                        <strong>{{ $message }}</strong>

                    </div>

            @endif

            <!--end card-body-->

            </div>

        </div>

        <!--end col-->

    </div>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Users</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Users</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="users">
                        <thead>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="not-export-col">Action</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('script')

    <script>
        let message = 'Your Message Will Show Here.';
        $(document).ready(function () {
            $('#users').DataTable({
               dom: "Blfrtip",
                buttons:
                    [
                        {
                            text : '<i class="fas fa-copy"></i>',
                            title: 'User Copy',
                            titleAttr : 'copy',
                            extend: 'copy',
                            orientation: 'portrait',
                            pageSize: 'A4',
                            messageTop: message,
                            className: 'btn-primary my-2 mr-2',
                            exportOptions: {
                                columns: ':visible:not(.not-export-col)',
                            },
                        },
                        {
                            text : '<i class="fas fa-file-csv"></i>',
                            title: 'User Csv',
                            titleAttr : 'Csv',
                            extend: 'csvHtml5',
                            orientation: 'portrait',
                            pageSize: 'A4',
                            messageTop: message,
                            className: 'btn-primary my-2 mr-2',
                            exportOptions: {
                                columns: ':visible:not(.not-export-col)',
                            },
                        },
                        {
                            text : '<i class="fas fa-file-excel"></i>',
                            title: 'User Excel',
                            titleAttr : 'Excel',
                            extend: 'excelHtml5',
                            orientation: 'portrait',
                            pageSize: 'A4',
                            messageTop: message,
                            className: 'btn-primary my-2 mr-2',
                            exportOptions: {
                                columns: ':visible:not(.not-export-col)',
                            },
                        },
                        {
                            text : '<i class="fas fa-file-pdf"></i>',
                            title: 'User PDF',
                            titleAttr : 'PDF',
                            extend: 'pdfHtml5',
                            orientation: 'portrait',
                            pageSize: 'A4',
                            messageTop: message,
                            className: 'btn-primary my-2 mr-2',
                            exportOptions: {
                                columns: ':visible:not(.not-export-col)',
                            },
                        },
                        {
                            text : '<i class="fas fa-print"></i>',
                            title: 'User Print',
                            titleAttr : 'Print',
                            extend: 'print',
                            orientation: 'portrait',
                            pageSize: 'A4',
                            messageTop: message,
                            className: 'btn-primary my-2 mr-2',
                            exportOptions: {
                                columns: ':visible:not(.not-export-col)',
                            },
                        },
                    ],



                "processing": true,
                "serverSide": true,
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                "autoFill": true,
                // "stateSave": true,
                // "scrollY": 200,
                // "paging": false,
                // "responsive": true,
                // "scrollX": true,
                "pagingType": "full_numbers",
                "ajax": {
                    "url": "{{ URL::route('users.index') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {"data": "id"},
                    {"data": "first_name"},
                    {"data": "email"},
                    {"data": "deleted_at"},
                    {"data": "created_at"},
                    {"data": "action", className: "action", colspan: "1",},
                ],
                order : [[4, 'desc']]
            });
        });
    </script>
    @include('flashy::message')

    <script></script>

@endsection

