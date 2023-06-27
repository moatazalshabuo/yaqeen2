@extends('layouts.master')
@section('title')
    فاتورة المشتريات
@endsection
@section('css')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />

    <!-- Interenal Accordion Css -->
    <link href="{{ URL::asset('assets/plugins/accordion/accordion.css') }}" rel="stylesheet" />
    <style>
        /* .overlay{
      position: absolute;
      top:0px;
      left: 0px;
      width: 100%;
      height: 100%;
      background-color: #eee;
      z-index: 9999;
    /*  display: none;*/
        /* } */
    </style>
    {{-- @endsection --}}

    @empty($data)
        @section('content')
            <div class=" text-center">
                <div class="card" style="margin:auto;margin-top: 100px;max-width: 450px;">
                    <div class="card-body">
                        <p>يس لديك اي فاتورة </p>
                        <p>هل تريد فتح فاتورة جديدة ل</p>
                        <a href="{{ route('Purchasesbill_create') }}" class="btn btn-primary text-white">فاتورة جديد </a>
                    </div>
                </div>
            </div>
        @endsection
    @endempty

    @empty(!$data)
        @section('num')
            <div class="btn-group m-1" role="group" aria-label="First group">
                <button type="button" class="btn btn-warning  btn-icon mx-1"><i class="mdi mdi-refresh"></i></button>
                <a type="button" class="btn btn-primary btn-icon"
                    @if ($prev) href='{{ route('Purchasesbill', $prev) }}' @else disabled @endif>
                    < </a>
                        <input type="text" class="form-control w-32" id="bill_id" value="{{ $data->id }}">
                        <a type="button" class="btn btn-primary btn-icon"
                            @if ($next) href='{{ route('Purchasesbill', $next) }}' @else disabled @endif>></a>
            </div>
        @endsection
        @section('page-header')
            <!-- breadcrumb -->


            <div class="breadcrumb-header row">
                <div class="my-auto col-md-4">
                    <div class="d-flex">
                        <h4 class="content-title mb-0 my-auto">فاتورة مشتريات</h4><span
                            class="text-muted mt-1 tx-13 mr-2 mb-0">{{ $data->name }}</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="btn-group" style="overflow-x:scroll">

                        <button type="button" data-effect="effect-scale" data-toggle="modal" data-target="#modaldemo1"
                            class="btn btn-primary m-1"><i class="mdi mdi-plus"></i> اضافة مادة </button>
                        <a href="{{ route('purchasesbill_edit', $data->id) }}" @if ($data->status) disabled @endif
                            class="btn btn-info m-1">تعديل فاتورة</a>
                        <button id="print-bill" class="btn btn-info m-1">طباعة فاتورة</button>
                        <button class="btn btn-info m-1" id="close-bill" @if ($data->status == 0) disabled @endif>حفظ
                            الفاتورة</button>
                        <a href="{{ route('Purchasesbill_create') }}" type="button" class="btn btn-danger m-1">فاتورة جديدة </a>
                    </div>
                </div>
            </div>
			<div>
				<small>مستلمة</small>
				<div class="main-toggle main-toggle-success @if($data->receipt == 1) on @endif" @if($data->receipt == 1) id="on" @else id="off" @endif>
					<span></span>
				</div>
			</div>
            <div class="row m-1">
                <div class="col-md-5 col-10">
                    <select id="custom" class="form-control select2"
                        @if ($data->status == 0) disabled @endif>
                        <option label="المورد">
                        </option>

                    </select>
                    <div class="text-danger" id="client-err"></div>
                </div>
                <div class="col-md-1 col-2">
                    <button class="btn btn-primary" data-target="#select2modal" data-toggle="modal"><i
                            class="mdi mdi-plus"></i></button>
                </div>
            </div>
            <!-- breadcrumb -->
        @endsection
        @section('content')
            <!-- row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="input-item">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>الصنف</label>
                                        <select name="material" class="form-control select2" id="mate">
                                            <option value="" >اختر الصنف</option>

                                            @foreach ($mate as $item)
                                                <option value="{{ $item->id }}">{{ $item->material_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger" id="product_error"></div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label>الكمية الموجودة</label>
                                        <input class="form-control" disabled placeholder="الكمية" id="old_quantity" type="number">

                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label>اخر سعر</label>
                                        <input class="form-control" id="old_price" disabled type="number">
                                    </div>
                                    <div class="col-md-2 "></div>
                                    <div class="col-md-3 col-6">
                                        <label>الكمية</label>

                                        <input class="form-control" @if ($data->status == 0) disabled @endif
                                            placeholder="الكمية" id="quantity" name="quantity" type="number">
                                        <div class="text-danger" id="q_error"></div>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <label>السعر الحالي</label>
                                        <input class="form-control" placeholder="السعر" value="0" type="number"
                                            id="price" name="price" @if ($data->status == 0) disabled @endif>
                                        <div class="text-danger" id="price_error"></div>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        اجمالي السعر
                                        <input class="form-control" disabled placeholder="الاجمالي" type="number"
                                            id="totel">
                                    </div>
                                    <div class="col-md-1">
                                        <br>
                                        <button class="btn btn-primary" id="addItem" type="button"><span
                                                class="spinner-border spinner-border-sm sp" style="display: none"></span><span
                                                class="text">حفظ</span></button>
                                    </div>
                                    <div class="text-warning war"></div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 col-md-10">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="table-responsive">
                                <table class="table mg-b-0 text-md-nowrap table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>ت</th>
                                            <th>الصنف</th>
                                            <th>الكمية</th>
                                            <th>التخفيض</th>
                                            <th>اجمالي سعر</th>
                                            <th>تاريخ المعاملة</th>
                                            <th>اخرى</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                        <div class="card-body" style="height: 330px;overflow-y:scroll">
                            <div class="table-responsive">
                                <table class="table mg-b-0 text-md-nowrap text-left">
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class="card card-primary ">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-4">
                                <div class="form-group p-1 ">
                                    الاجمالي : <input type="number" disabled value="0" class="form-control"
                                        id="total">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-4">
                                <div class="form-group p-1 ">
                                    الخالص : <input type="number" disabled value="0" class="form-control" id="sincere">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-4">
                                <div class="form-group p-1 ">
                                    المتبقي : <input type="number" disabled value="0" class="form-control" id="Residual">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- Container closed -->
            </div>
            <!-- main-content closed -->
            <div class="modal" id="select2modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title">اضافة زبون</h6><button aria-label="Close" class="close"
                                data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <h6>ادخل البيانات</h6>
                            <!-- Select2 -->
                            <form id="form-client-add">
                                @csrf
                                <div class="form-group">
                                    <label>الاسم</label>
                                    <input type="text" name="name" class="form-control">
                                    <p id="name_err" class="text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <label>رقم الهاتف</label>
                                    <input type="number" name="phone" id="phone_couts" class="form-control">
                                    <p id="phone_err" class="text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <label>الايميل(اختياري)</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                    <p id="phone_err" class="text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <label>الموقع(اختياري)</label>
                                    <input type="text" name="address" id="address" class="form-control">
                                    <p id="phone_err" class="text-danger"></p>
                                </div>
                            </form>
                            <!-- Select2 -->
                        </div>
                        <div class="modal-footer">
                            <button class="btn ripple btn-primary" id="save-client" type="button"><span
                                    class="spinner-border spinner-border-sm sp" style="display: none"></span><span
                                    class="text">حفظ</span></button>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">الغاء</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ================== add rawmaterial ========== -->
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
                                <div class="form-group">
                                    <label for="exampleInputEmail1">اسم المادة</label>
                                    <input type="text" class="form-control" id="material_name" name="material_name" required>
                                </div>
                                <div class="text text-danger error_add" id="error_material_name"></div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">نوع الكمية</label>
                                    <select name="hisba_type" id="hisba_type" class="form-control">
                                        <option value="">حدد نوع الكمية</option>
                                        <option value="1">بالمتر</option>
                                        <option value="2">بالطرف</option>
                                    </select>
                                </div>
                                <div class="text text-danger error_add" id="error_hisba_type"></div>

                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">كمية المخزون</label>

                                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                                </div>
                                <div class="text text-danger error_add" id="error_quantity"></div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">السعر</label>
                                    <input type="number" class="form-control" id="price_mate" name="price" required>
                                </div>
                                <div class="text text-danger error_add" id="error_price_mate"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" id="add-mate"><span
                                        class="spinner-border spinner-border-sm sp" style="display: none"></span><span
                                        class="text">تاكيد</span></button>
                                <button type="button" class="btn btn-outline-danger close_add"
                                    data-dismiss="modal">إغلاق</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- ===================== end ============== -->
        @endsection

        @section('js')
			<script>
				$(function(){
					$('.main-toggle').on('click', function() {

					})
				})
			</script>
            @include('purchases/ajax')
        @endsection
    @endempty
