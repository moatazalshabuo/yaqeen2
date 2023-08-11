@extends('layouts.master')
@section('title')
    المواد الخام
@endsection
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        /* .m3{
      display: none !important;
     } */
    </style>
@endsection
@section('title')
    المواد الخام
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المواد الخام</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    ادارة المواد التالفة</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            {{-- <div class="pr-1 mb-3 mb-xl-0">
			<button type="button" class="btn btn-info btn-icon ml-2"><i class="mdi mdi-filter-variant"></i></button>
		</div> --}}
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" id="refresh" class="btn btn-danger btn-icon ml-2"><i
                        class="mdi mdi-refresh"></i></button>
            </div>
            @can("اضافة مادة خام")
                <div class="pr-1 mb-3 mb-xl-0">
                    <button type="button" data-effect="effect-scale" data-toggle="modal" data-target="#modaldemo1"
                        class="btn btn-primary ml-2"><i class="mdi mdi-plus"></i> اضافة مادة </button>
                </div>
            @endcan
        </div>
    </div>
@endsection
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    @if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="crad-header p-5">
                    <form method="POST" action="{{ route('cm.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>اختر المادة</label>
                                    <select class="form-control sel" name="raw_id">
                                        <option value="">اختر مادة </option>
                                        @foreach ($raws as $item)
                                        <option value="{{ $item->id }}">{{ $item->material_name }} / الكمية الحالية ({{ $item->quantity }})</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="type" value="0">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>الكمية</label>
                                <input type="number" min="0" step="any"  max="9999999999" class="form-control" name="quantity">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary">اضافة <i class="fa fa-plus"></i></button>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="card-body" style="height:400px;overflow-y: scroll;">
                    <div class="table-responsive">

                        <table class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">ر.ت</th>
                                    <th class="border-bottom-0">اسم المادة</th>
                                    <th class="border-bottom-0">كمية </th>
                                    <th class="border-bottom-0">السعر</th>
                                    <th>المستخدم</th>
                                    <th class="border-bottom-0">العمليات</th>
                                </tr>

                            </thead>
                            <tbody id="myTable">
                                @foreach ($cm as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->raw->material_name }}</td>
                                        <td> {{ $item->quantity }}</td>
                                        <td>{{ $item->quantity*$item->raw->pace_price  }}</td>
                                        <td>{{ $item->created_by }}</td>
                                        <td>
                                            <td>
                                                <form action="{{ route('cm.destroy',$item->id) }}" method="post">
                                                    @csrf
                                                    @method("DELETE")
                                                    <button class="btn btn-outline-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->

        <!--div-->

    </div>
@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
    <script>

    </script>
@endsection
