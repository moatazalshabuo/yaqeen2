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
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرواتب</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    الرواتب</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                <button class="btn btn-danger" data-toggle="modal" data-target="#pull">سحب من الراتب</button>
            </div>
            <div class="mb-3 mb-xl-0">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modaldemo1">سحب راتب شهري</button>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
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
    <!-- row opened -->
    <div class="row row-sm">
        <!--div-->
        <div class="col-md-3">
            <form action="" method="get">
                <div class="form-group">
                    <input type="month" name="date" class="form-control">
                </div>
                <div class="form-group">
                    <label>عرض الكل</label>
                    <input type="checkbox" name="all" value="1">
                </div>
                <input type="submit" class="btn btn-primary">
            </form>
        </div>
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">رواتب الشهر</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table text-md-nowrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="border-bottom-0">الاسم</th>
                                    <th class="border-bottom-0">الشهر</th>
                                    <th class="border-bottom-0">الراتب</th>
                                    <th class="border-bottom-0">الاضافي</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">المتبقي</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salary as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->user_name }}</td>
                                        <td>{{ $item->mount }}</td>
                                        <td>{{ $item->salary }}</td>
                                        <td>{{ $item->plus }}</td>
                                        <td>{{ $item->dept_on }}</td>
                                        <td>{{ $item->still }}</td>
                                        <td><a href="{{ route('delete.salary', $item->id) }}" class="btn btn-danger"><i
                                                    class="fa fa-trash"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">الديون الشهرية</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap table1">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="border-bottom-0">الاسم</th>
                                    <th class="border-bottom-0">القيمة</th>
                                    <th class="border-bottom-0">التاريخ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dept as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td><a href="{{ route('delete.depts',$item->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->

    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
    <div class="modal" id="modaldemo1">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title"> راتب موظف</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="text-danger error" id="error"></div>
                    <form id="form-salary">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">الموظف</label>
                                    <select class="form-control" name="user_id" id="user_id-1">
                                        <option value="">اختر المستخدم</option>
                                        @foreach ($users as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error" id="error-user_id"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الشهر</label>
                                    <input type="month" name="mounth" id="mounth" class="form-control">
                                </div>
                                <div class="text-danger error" id="error-mounth"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الراتب الاساسي</label>
                                    <input type="number" disabled id="salary-1" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الخالص</label>
                                    <input type="number" disabled id="dept-1" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>الخصم</label>
                                    <input type="number" name="dept_on" id="dept-on" value="0"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>الاضافي</label>
                                    <input type="number" name="plus" id="plus" value="0"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>الاجمالي</label>
                                    <input type="number" name="totle" id="totel" value="0"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" id="save_salary" type="button">
                        <span class="spinner-border spinner-border-sm sp" style="display: none">
                        </span>
                        <span class="text">حفظ</span>
                    </button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="pull">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">اضافة بيانات راتب موظف</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="text-danger error" id="error"></div>
                    <form id="form-salary-pull">
                        @csrf
                        <div class="form-group">
                            <label for="">الموظف</label>
                            <select class="form-control" id="user_id-2" name="user_id">
                                <option value="">اختر المستخدم</option>
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger error" id="error-user_id-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">عليه </label>
                                    <input type="number" step="any" disabled id="on-him-2" class="form-control"
                                        placeholder="القيمة">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">القيمة </label>
                                    <input type="number" step="any" name="price" class="form-control"
                                        placeholder="القيمة">
                                </div>
                                <div class="text-danger error" id="error-price-2"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" id="save-dept" type="button">
                        <span class="spinner-border spinner-border-sm sp" style="display: none">
                        </span>
                        <span class="text">حفظ</span>
                    </button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
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
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script>
        $(function() {
            $("#form-salary-pull,#form-salary").submit(function(e) {
                e.preventDefault()
            })

            function moveButton(selector1, selector2) {
                selector1.show()
                selector2.hide()
            }

            function stopButton(selector1, selector2) {
                selector2.show()
                selector1.hide()
            }
            // $("#save_salary").click(function() {
            //     $(".error").text("")
            //     moveButton($("#save_salary .sp"), $("#save_salary .text"))
            //     axios.post("/salary/save", $("#form-salary").serialize()).then((res) => {
            //         if (res.data.error == 0) {
            //             location.reload()

            //         } else {
            //             $("#error").text("لايمكن ادخال المستخدم مرتين")
            //             stopButton($("#save_salary .sp"), $("#save_salary .text"))
            //         }
            //     }).catch((res) => {
            //         data = res.response.data.errors
            //         $("#error-user_id").text(data.user_id)
            //         $("#error-type_salary").text(data.typr_salary)
            //         $("#error-salary").text(data.salary)
            //         stopButton($("#save_salary .sp"), $("#save_salary .text"))
            //     })
            // })

            $("#user_id-2").change(function() {
                var id = $(this).val()
                axios.get(`/salary/get/${id}`).then((res) => {
                    data = res.data

                    $("#on-him-2").val(data.dept)
                })
            })

            $("#save-dept").click(function() {
                $(".error").text("")
                moveButton($("#save-dept .sp"), $("#save-dept .text"))
                axios.post('/salary/dept', $("#form-salary-pull").serialize()).then((res) => {
                    location.reload()
                }).catch((res) => {
                    console.log(res)
                    stopButton($("#save-dept .sp"), $("#save-dept .text"))
                    var data = res.response.data.errors
                    $("#error-user_id-2").text(data.user_id)
                    $("#error-price-2").text(data.price)
                })
            })

            $("#user_id-1").change(function() {
                var id = $(this).val()
                axios.get(`/salary/get/${id}`).then((res) => {
                    data = res.data

                    $("#dept-1").val(data.dept)
                    $("#salary-1").val((data.type_salary == 1) ? data.totel_salary : data.salary)
                })
            })

            function count_totel() {
                var dept_on = ($("#dept-on").val() == undefined) ? 0 : parseInt($("#dept-on").val())
                var plus = ($("#plus").val() == undefined) ? 0 : parseInt($("#plus").val())
                var salary = ($("#salary-1").val() == undefined) ? 0 : parseInt($("#salary-1").val())
                $("#totel").val((salary + plus) - dept_on)
            }
            $("#dept-on,#plus").keyup(count_totel);

            $("#save_salary").click(function() {
                moveButton($("#save_salary .sp"), $("#save_salary .text"))
                axios.post("/salary/save/salary", $("#form-salary").serialize()).then((res) => {
                    stopButton($("#save_salary .sp"), $("#save_salary .text"))
                    location.reload()
                }).catch((res) => {
                    stopButton($("#save_salary .sp"), $("#save_salary .text"))
                    var data = res.response.data.errors
                    $("#error-user_id").text(data.user_id);
                    $("#error-mounth").text(data.mounth);
                })
            })
        })
    </script>
@endsection
