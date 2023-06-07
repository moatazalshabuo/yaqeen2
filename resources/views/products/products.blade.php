@extends('layouts.master')
@section('title')
    المنتجات
@endsection
@section('css')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">ادرة المنتجات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                </span>
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
            @if (Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                <div class="pr-1 mb-3 mb-xl-0">
                    <button type="button" data-effect="effect-scale" data-toggle="modal" data-target="#modaldemo1"
                        class="btn btn-primary ml-2"><i class="mdi mdi-plus"></i> اضافة منتج </button>
                </div>
            @endif
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
                    <input type="text" id="myInput" class="form-control" placeholder="البحث با اسم المنتج">
                </div>
                <div class="card-body" style="height:400px;overflow-y: scroll;">
                    <div class="table-responsive">

                        <table class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">ر.ت</th>
                                    <th class="border-bottom-0">اسم المنتج</th>

                                    <th class="border-bottom-0">نوع الكمية</th>
                                    <th>المستخدم</th>
                                    <th class="border-bottom-0">ادارة بيانات المنتج</th>
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
                    <form action="{{ route('products.store') }}" id="form-add" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">اسم المنتج</label>
                                        <input type="text" class="form-control" placeholder="اسم المادة" id="name"
                                            name="name" required>
                                    </div>
                                    <div class="text text-danger error_add" id="error_name"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">نوع الكمية</label>
                                        <select name="type" id="type" class="form-control ">
                                            <option value="">حدد نوع الكمية</option>
                                            <option value="0">المتر</option>
                                            <option value="1">القطعة</option>
                                            <option value="2">المتر المربع</option>
                                        </select>
                                    </div>
                                    <div class="text text-danger error_add" id="error_hisba_type"></div>
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

        <div class="modal" id="edit-prodect">
            <div class="modal-dialog modal-danger" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header bg-primary">
                        <h6 class="modal-title text-white">تعديل </h6><button aria-label="Close" class="close"
                            data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="form-edit" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">اسم المادة</label>
                                        <input type="text" class="form-control" id="name-edit" name="name"
                                            required>
                                    </div>
                                    <div class="text text-danger error_edit" id="error-name-edit"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">نوع الكمية</label>
                                        <select name="type" id="type-edit" class="form-control">
                                            <option value="">حدد نوع الكمية</option>
                                            <option value="0">المتر</option>
                                            <option value="1">القطعة</option>
                                            <option value="2">المتر المربع</option>
                                        </select>
                                        <input type="hidden" name="id" id="id-edit">
                                    </div>
                                    <div class="text-danger error_edit" id="error-type-edit"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" id="update-product"><span
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
    {{-- <button type="button" class="btn btn-primary">Large modal</button> --}}

    <div id="manage-model" class="modal fade bd-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">

            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h6 class="modal-title text-white">ادارة منتج </h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="model-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="main-content-label mg-b-5 mb-2">
                                        اضافة اوجه المنتج
                                    </div>
                                    <form id="form-faces">
                                        @csrf
                                        <div class="input-group">
                                            <input class="form-control" placeholder="عنوان الوجه" name="title"
                                                id="title-face" type="text">
                                            <input type="hidden" name="id_product" class="id-product">
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" id="add-face" type="button">
                                                    <span class="spinner-border spinner-border-sm sp"
                                                        style="display: none"></span>
                                                    <span class="input-group-btn text"><i class="fa fa-plus"></i></span>
                                                </button>
                                            </span>
                                        </div>
                                        <div class="face-title-error text-danger"></div>
                                    </form>
                                    {{-- start list group --}}
                                    <div class="list-group" id="faces" style="max-height: 350px;overflow-y: scroll;">

                                    </div>
                                    {{--  end list group   --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="main-content-label mg-b-5 mb-2">
                                        اضافة المواد لوجه المنتج
                                    </div>
                                    <div class="col-md-12">
                                        <label>مواد الخام </label>
                                        <input type="radio" name="checktype" checked value="1" class="checktype">
                                        <label>عمليات التصنيع </label>
                                        <input type="radio" name="checktype" value="2" class="checktype">
                                    </div>
                                    <form id="form-material-face">
                                        @csrf
                                        <div class="row">

                                            <div class="col-md-4">
                                                <p class="mg-b-10">اختر الوجه</p>
                                                <select name="face" id="faces-item" class="form-control">
                                                    <option value="">حدد الوجه</option>

                                                </select>
                                                <div class="fme text-danger" id="fme-face"></div>
                                            </div>
                                            <div id="div-material" class="col-md-7">
                                                <div class="row">
                                                    <div class="col-md-6 mg-b-20 mg-lg-b-0">
                                                        <p class="mg-b-10">اختر المواد الخام</p>
                                                        <select class="form-control" name="material[]"
                                                            id="materials-item" multiple="multiple">

                                                        </select>
                                                        <div class="fme text-danger" id="fme-material"></div>
                                                    </div>
                                                    <div class="col-md-6 mg-b-20 mg-lg-b-0">
                                                        <p class="mg-b-10">الكمية للمتر </p>
                                                        <input type="number" name="quantity" id="quantity-face"
                                                            step="any" class="form-control" placeholder="الكمية">
                                                        <div class="fme text-danger" id="fme-quantity"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="div-tool" class="col-md-4 mg-b-20 mg-lg-b-0">
                                                <p class="mg-b-10">اختر عمليات التصنيع</p>
                                                <select class="form-control" name="tool[]" id="tool-item"
                                                    multiple="multiple">
                                                </select>
                                                <div class="fme text-danger" id="fme-tool"></div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-sm" id="add-material-face" type="button">
                                            <span class="spinner-border spinner-border-sm sp"
                                                style="display: none"></span>
                                            <span class="input-group-btn text"><i class="fa fa-plus"></i></span>
                                        </button>
                                    </form>
                                    {{-- start list group --}}
                                    <p>مواد المنتج</p>
                                    <div class="list-group" id="materials" style="max-height: 175px;overflow-y: scroll;">

                                    </div>
                                    <p>عمليات المنتج</p>
                                    <div class="list-group" id="tools" style="max-height: 175px;overflow-y: scroll;">

                                    </div>
                                    {{--  end list group   --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-foote p-3">
                    <button class="btn btn-primary" id="save-all">حفظ </button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/js/axois.js') }}"></script>
    <script>
        $(function() {
            $("#faces-item").select2({
                dropdownParent: $('#manage-model'),

            })
            $("#materials-item,#tool-item").select2({
                dropdownParent: $('#manage-model'),
                placeholder: 'اختر المواد',
                searchInputPlaceholder: 'بحث'
            })
            // $(".select2-search__field").attr("placeholder","اختر المواد")
        })
    </script>
    @include('products.ajax')
@endsection
