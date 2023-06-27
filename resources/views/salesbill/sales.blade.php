@extends('layouts.master')
@section('title')
    فاتورة المبيعات
@endsection
@section('css')
    <!-- Interenal Accordion Css -->
    <link href="{{ URL::asset('assets/plugins/accordion/accordion.css') }}" rel="stylesheet" />
    <style>

    </style>
@endsection

@empty($data)
    @section('content')
        <div class=" text-center">
            <div class="card" style="margin:auto;margin-top: 100px;max-width: 450px;">
                <div class="card-body">
                    <p>يس لديك اي فاتورة </p>
                    <p>هل تريد فتح فاتورة جديدة ل</p>
                    <a href="{{ route('salesbiil_create') }}" class="btn btn-primary text-white">فاتورة جديد </a>
                </div>
            </div>
        </div>
    @endsection
@endempty

@empty(!$data)
    @section('num')

    @endsection
    @section('page-header')
        <input type="hidden" id="salesbill" value="{{ $data->id }}">
        <!-- breadcrumb -->
        <div class="row mt-2">
            <div class="btn-group">

                <button type="button" class="btn btn-warning  btn-icon ml-2" id="refresh"><i class="mdi mdi-refresh"></i></button>
                <a type="button" class="btn btn-primary btn-icon ml-1"
                    @if ($first && $data->id != $first) href='{{ route('salesbill', $first) }}' @else disabled @endif>
                    << </a>
                        <a type="button" class="btn btn-primary btn-icon"
                            @if ($prev) href='{{ route('salesbill', $prev) }}' @else disabled @endif>
                            < </a>
                                <input type="text" class="form-control" id="bill_id" value="{{ $data->id }}">
                                <a type="button" class="btn btn-primary btn-icon ml-1"
                                    @if ($next) href='{{ route('salesbill', $next) }}' @else disabled @endif>></a>
                        </a>
                        <a type="button" class="btn btn-primary btn-icon"
                            @if ($last && $data->id != $last) href='{{ route('salesbill', $last) }}' @else disabled @endif>>></a>
            </div>
        </div>
        <div class="breadcrumb-header row">
            <div class="my-auto col-md-5">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">فاتورة المبيعات</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0">{{ $data->name }}/{{ $data->created_at }}</span>
                </div>
            </div>
            <div class="btn-group left-content col-md-7" style="overflow-x:scroll">
                <button class="btn btn-info ml-2" id="print-bill">طباعة الفاتورة</button>
                @if ($data->type == 0)
                    <button class="btn btn-info ml-2" id="start-work">بداية امر عمل</button>
                @else
                    <button class="btn btn-info ml-2" id="print-work">طباعة امر عمل</button>
                @endif
                <a href="{{ route('salesbill_edit', $data->id) }}" @if ($data->status) disabled @endif
                    class="btn btn-info ml-2">تعديل الفاتورة</a>
                <button class="btn btn-info ml-2" id="close-bill" @if ($data->status == 0) disabled @endif>حفظ
                    الفاتورة</button>
                <a href="{{ route('salesbiil_create') }}" type="button" class="btn btn-danger  ml-2">فاتورة جديدة </a>
            </div>
        </div>
        <div>
            <small>cnc</small>
            <div class="main-toggle main-toggle-success @if ($data->type == 1) on @endif"
                @if ($data->type == 1) id="on" @else id="off" @endif>
                <span></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 col-10">
                <form id="form-client">
                    <select class="form-control select2-no-search mb-1" name="client" id="client"
                        @if ($data->status == 0) disabled @endif>
                        <option label="الزبون">
                        </option>
                    </select>
                </form>
                <p class="text-danger" id="client-err"></p>
            </div>
            <div class="col-md-1 col-2">
                <button class="btn btn-primary" data-target="#select2modal" data-toggle="modal">
                    <i class="mdi mdi-plus"></i></button>
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
                                @if ($data->type == 1)
                                    <div class="col-md-4">
                                        <label>العملية</label>
                                        <select @disabled($data->status == 0) class="form-control" name="cnc_tools"
                                            id="cnc-tools">
                                            <option value="">اختر الاداء</option>
                                            @foreach ($cnc as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="error text-danger" id="cnc-tools-error"></div>
                                    </div>
                                    <input type="hidden" name="sales_id" value="{{ $data->id }}">
                                    <div class="col-md-1">
                                        <label for="">متر مربع</label>
                                        <input type="checkbox" id="check-m-3">
                                    </div>
                                    <div class="col-md-2 wl-cnc" style="display: none">
                                        <input type="number" @disabled($data->status == 0) class="form-control w-cnc"
                                            placeholder="الطول" id="h-cnc">
                                        <input type="number" id="w-cnc" placeholder="لعرض" @disabled($data->status == 0)
                                            class="form-control w-cnc">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>الكمية</label>
                                            <input type="number" class="form-control" @disabled($data->status == 0)
                                                id="quantity_cnc" name="quantity">
                                            <input type="hidden" id="price_cnc" value="0">
                                        </div>
                                        <div class="error text-danger" id="quantity_error"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>السعر</label>
                                            <input type="number" class="form-control" disabled id="price_totel">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>الوصف</label>
                                            <input type="text" @disabled($data->status == 0) class="form-control"
                                                placeholder="الوصف" name="descripe">
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-4">
                                        <label>الصنف</label>

                                        <select name="product" @disabled($data->status == 0) class="form-control"
                                            id="product">
                                            <option value="">اختر الصنف</option>
                                            @foreach ($product as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger" id="product_error"></div>

                                    </div>
                                @endif
                                <div class="col-md-1">
                                    <br>
                                    <button class="btn btn-primary" id="addItem" type="button">
                                        <span class="spinner-border spinner-border-sm sp" @disabled($data->status == 0)
                                            style="display: none"></span><span class="text">حفظ</span></button>
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


                    </div>
                    <div class="card-body" style="height: 330px;overflow-y:scroll">
                        <div class="table-responsive">
                            <table class="table mg-b-0 text-md-nowrap text-left">
                                <thead>
                                    <tr>
                                        <th>ت</th>
                                        <th>الصنف</th>
                                        <th>التفاصيل</th>
                                        <th>الكمية</th>
                                        <th>التخفيض</th>
                                        <th>اجمالي سعر</th>
                                        <th>الحالة</th>
                                        <th>تاريخ المعاملة</th>
                                        <th>اخرى</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="card card-primary p-1">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-4">
                            <div class="form-group p-1">
                                الاجمالي : <input type="number" disabled value="0" class="form-control"
                                    id="totel">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-4">
                            <div class="form-group p-1 ">
                                الخالص : <input type="number" @if ($data->status == 0) disabled @endif
                                    class="form-control" id="sincere">
                            </div>

                            <p class="text-danger" id="sincere-err"></p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-4">
                            <div class="form-group p-1">
                                المتبقي : <input type="number" disabled value="0" class="form-control" id="Residual">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- row closed -->
        </div>
        </div>
        <div class="modal" id="select2modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">اضافة زبون</h6><button aria-label="Close" class="close"
                            data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <h6>ادخل البيانات</h6>
                        <form id="form-client-add">
                            @csrf
                            <div class="form-group">
                                <label>الاسم</label>
                                <input type="text" name="name" class="form-control">
                                <p id="name_err" class="text-danger"></p>
                            </div>
                            <div class="form-group">
                                <label>رقم الهاتف</label>
                                <input type="number" name="phone" id="phone" class="form-control">
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

        <div class="modal" id="salesbill-model" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title" id="product-name">اضافة منتج للفاتورة</h6><button aria-label="Close"
                            class="close close-modal" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <h6 id="product-name" class="title-product"></h6>
                        <div class="row m-0">
                            <div class="col-md-4">
                                <label>التكلفة</label>
                                <input type="number" disabled class="form-control" id="coust">
                            </div>
                            <div class="col-md-4">
                                <label>اجمالي القيمة</label>
                                <input type="number" disabled class="form-control" id="price">
                            </div>
                            <div class="col-md-12 m-3">
                                <form id="form-product">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="from-group">
                                                <label>بيان المنتج</label>
                                                <input type="text" class="form-control" name="descripe"
                                                    placeholder="تفاصيل المنتج">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="from-group">
                                                <label>العدد</label>
                                                <input type="number" class="form-control count-totel" id="count"
                                                    name="count" value="1" placeholder="عدد الطلب">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="product_id" id="product_id">
                                    <input type="hidden" name="sales_id" value="{{ $data->id }}">
                                    <div id="content-form">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-primary" id="store-sales" type="button">حفظ</button>
                        <button class="btn ripple btn-secondary close-modal" data-dismiss="modal"
                            type="button">اغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('js')
        <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

        @include('salesbill/ajax')
    @endsection
@endempty
