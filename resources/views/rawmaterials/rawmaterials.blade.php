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
                    ادارة المواد الخام</span>
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()"
                        placeholder="البحث با اسم المنتج">
                </div>
                <div class="card-body" style="height:400px;overflow-y: scroll;">
                    <div class="table-responsive">

                        <table class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">ر.ت</th>
                                    <th class="border-bottom-0">اسم المادة</th>

                                    <th class="border-bottom-0">نوع الكمية</th>
                                    <th class="border-bottom-0">كمية المخزون</th>
                                    <th class="border-bottom-0">السعر</th>
                                    <th class="border-bottom-0">سعر القطعة</th>
                                    <th>المستخدم</th>
                                    <th class="border-bottom-0">العمليات</th>

                                </tr>

                            </thead>
                            <tbody id="myTable">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->

        <!--div-->

        <!-- Basic modal -->
        <div class="modal" id="modaldemo1">
            <div class="modal-dialog " role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header bg-primary text-white">
                        <h6 class="modal-title text-white">اضافة </h6><button aria-label="Close" class="close"
                            data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('rawmaterials.store') }}" id="form-add" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">اسم المادة</label>
                                        <input type="text" class="form-control" placeholder="اسم المادة" id="material_name"
                                            name="material_name" required>
                                    </div>
                                    <div class="text text-danger error_add" id="error_material_name"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">نوع الكمية</label>
                                        <select name="hisba_type" id="hisba_type" class="form-control">
                                            <option value="">حدد نوع الكمية</option>
                                            <option value="1">متر مربع</option>
                                            <option value="2">متر</option>
                                            <option value="3">قطعة</option>
                                        </select>
                                    </div>
                                    <div class="text text-danger error_add" id="error_hisba_type"></div>
                                </div>
                                <div class="col-md-6 width-div" style="display: none">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">العرض</label>
                                        <input type="number" class="form-control m2" id="width" placeholder="طول القطعة" name="width"
                                            required>
                                    </div>
                                    <div class="text text-danger error_add" id="error_width"></div>
                                </div>
                                <div class="col-md-6 hiegth-div" style="display: none">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">الطول</label>
                                        <input type="number" class="form-control m2" id="hiegth" placeholder="طول القطعة" name="hiegth"
                                            required>
                                    </div>
                                    <div class="text text-danger error_add" id="error_hiegth"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">كمية المخزون</label>
                                        <input type="number" class="form-control m2" id="quantity" placeholder="كمية المخزون" name="quantity"
                                            required>
                                    </div>
                                    <div class="text text-danger error_add" id="error_quantity"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">السعر</label>
                                        <input type="number" class="form-control" id="price_meta" placeholder="السعر" name="price"
                                            required>
                                    </div>
                                    <div class="text text-danger error_add" id="error_price"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" id="add-mate"><span
                                    class="spinner-border spinner-border-sm sp" style="display: none"></span><span
                                    class="text">تأكيد</span></button>
                            <button type="button" class="btn btn-outline-danger close_add"
                                data-dismiss="modal">إغلاق</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" id="edit_material">
            <div class="modal-dialog modal-danger" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header bg-primary">
                        <h6 class="modal-title text-white">تعديل </h6><button aria-label="Close" class="close"
                            data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('materialupdate') }}" id="form-edit" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">اسم المادة</label>
                                        <input type="text" class="form-control" id="material_name_e"
                                            name="material_name" required>
                                            <input type="text" class="form-control" id="id_e"
                                            name="id" required>
                                    </div>
                                    <div class="text text-danger error_edit" id="error_material_name_e"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">نوع الكمية</label>
                                        <select name="hisba_type" id="hisba_type_e" class="form-control">
                                            <option value="">حدد نوع الكمية</option>
                                            <option value="1">متر مربع</option>
                                            <option value="2">متر</option>
                                            <option value="3">قطعة</option>
                                        </select>
                                    </div>
                                    <div class="text text-danger error_edit" id="error_hisba_type_e"></div>
                                </div>
                                <div class="col-md-6 width-div" style="display: none">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">العرض</label>
                                        <input type="number" class="form-control m2" id="width_e" placeholder="طول القطعة" name="width"
                                            required>
                                    </div>
                                    <div class="text text-danger error_edit" id="error_width_e"></div>
                                </div>
                                <div class="col-md-6 hiegth-div" style="display: none">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">الطول</label>
                                        <input type="number" class="form-control m2" id="hiegth_e" placeholder="طول القطعة" name="hiegth"
                                            required>
                                    </div>
                                    <div class="text text-danger error_edit" id="error_hiegth_e"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">كمية المخزون</label>
                                        <input type="number" class="form-control" id="quantity_e" name="quantity"
                                            required>
                                    </div>
                                    <div class="text text-danger error_edit" id="error_quantity_e"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">السعر</label>
                                        <input type="number" class="form-control" id="price_e" name="price"
                                            required>
                                    </div>
                                    <div class="text text-danger error_edit" id="error_price_e"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" id="update"><span
                                    class="spinner-border spinner-border-sm sp" style="display: none"></span><span
                                    class="text">تأكيد</span></button>
                            <button type="button" class="btn btn-outline-danger close_edit"
                                data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Basic modal -->
    </div>
@endsection

@section('js')
    <!-- Internal Data tables -->
    @include('rawmaterials.ajax')
@endsection
