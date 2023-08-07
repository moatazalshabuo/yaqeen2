@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('title')
    ادارة الزبائن
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الزبائن</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                    الرئيسية</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
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
                <div class="card-header pb-0">
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <button class="btn btn-primary" data-target="#select2modal" data-toggle="modal">اضافة زبون</button>

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if (@isset($user) && !@empty($user) && count($user) > 0)
                            <table id="example1" class="table key-buttons text-md-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">ر.ت</th>
                                        <th class="border-bottom-0">اسم الزبون</th>
                                        <th class="border-bottom-0">رقم الهاتف</th>
                                        <th class="border-bottom-0">البريد الالكتروني</th>
                                        <th class="border-bottom-0">عنوان السكن</th>

                                    </tr>

                                </thead>

                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    <tr>



                                        @foreach ($user as $dates)
                                            @php
                                                $i++;
                                            @endphp



                                            </td>
                                            <td>{{ $i }}</td>
                                            <td>{{ $dates->name }}</td>
                                            <td>{{ $dates->phone }}</td>
                                            <td>{{ $dates->email }}</td>
                                            <td>{{ $dates->adress }}</td>
                                            <td>

                                                <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                    data-id="{{ $dates->id }}" data-name="{{ $dates->name }}"
                                                    data-phone="{{ $dates->phone }}" data-email="{{ $dates->email }}"
                                                    data-adress="{{ $dates->adress }}" data-toggle="modal"
                                                    href="#exampleModal2" title="تعديل"><i class="las la-pen"></i></a>

                                                <button class="btn btn-outline-danger btn-sm "
                                                    data-pro_id="{{ $dates->id }}"
                                                    data-product_name="{{ $dates->name }}" data-toggle="modal"
                                                    data-target="#modaldemo9">حذف</button>
                                            </td>
                                    </tr>
                        @endforeach
                    @else
                        <span> لايوجد زبائن مخزنين بالنظام</span>
                        @endif

                        </tbody>






                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->

        <!--div-->
        <!-- Basic modal -->

        <!-- End Basic modal -->
    </div>

    <div class="modal" id="select2modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">اضافة زبون</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <h6>ادخل البيانات</h6>
                    <!-- Select2 -->
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>الاسم</label>
                            <input type="text" name="name" class="form-control">
                            <p id="name_err" class="text-danger"></p>
                        </div>
                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input type="number" name="phone" class="form-control">
                            <p id="phone_err" class="text-danger"></p>
                        </div>
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control">
                            <p id="email_err" class="text-danger"></p>
                        </div>
                        <div class="form-group">
                            <label>العنوان</label>
                            <input type="text" name="adress" class="form-control">
                            <p id="adress_err" class="text-danger"></p>
                        </div>

                        <!-- Select2 -->
                        <div class="modal-footer">
                            <button class="btn ripple btn-primary" type="submit">حفظ</button>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">الغاء</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- edit -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات الزبون</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ url('clients/update') }}" method="post" autocomplete="off">
                        {{ method_field('patch') }}
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="">
                            <label for="recipient-name" class="col-form-label">اسم الزبون:</label>
                            <input class="form-control" name="name" id="name" type="text">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">رقم الهاتف:</label>
                            <input type="phone" class="form-control" id="phone" name="phone">
                        </div>

                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>العنوان</label>
                            <input type="text" name="adress" id="adress" class="form-control">
                        </div>


                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">تاكيد</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="modaldemo9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">حذف الزبون</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('clients/destroy') }}" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>هل انت متاكد من عملية الحذف ؟</p><br>
                        <input type="hidden" name="pro_id" id="pro_id" value="">
                        <input class="form-control" name="product_name" id="product_name" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- row closed -->

    <!-- Container closed -->

    <!-- main-content closed -->
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
    <!--Internal  Datatable js -->

    <script>
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var section_name = button.data('name')
            var description = button.data('phone')
            var email = button.data('email')
            var adress = button.data('adress')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(section_name);
            modal.find('.modal-body #phone').val(description);
            modal.find('.modal-body #email').val(email);
            modal.find('.modal-body #adress').val(adress);

        })
    </script>

    <script>
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var pro_id = button.data('pro_id')
            var product_name = button.data('product_name')
            var modal = $(this)

            modal.find('.modal-body #pro_id').val(pro_id);
            modal.find('.modal-body #product_name').val(product_name);
        })
    </script>

    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
@endsection
