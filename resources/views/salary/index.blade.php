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
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    عرض المستخدمين</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-info btn-icon ml-2"><i class="mdi mdi-filter-variant"></i></button>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-danger btn-icon ml-2"><i class="mdi mdi-star"></i></button>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-warning  btn-icon ml-2"><i class="mdi mdi-refresh"></i></button>
            </div>
            <div class="mb-3 mb-xl-0">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modaldemo1">اضافة بيانات راتب </button>
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
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">جدول المستخدمين</h4>
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
                                    <th class="border-bottom-0">نوع الراتب</th>
                                    <th class="border-bottom-0">النسبة / الراتب الشهري</th>
                                    <th class="border-bottom-0">اجمالي الراتب الحالي</th>
                                    <th class="border-bottom-0">الدين الحالي</th>
                                    <th class="border-bottom-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salary as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @if ($item->type_salary == 1)
                                                بالنسبة
                                            @else
                                                راتب شهري
                                            @endif
                                        </td>

                                        <td>
                                            @if ($item->type_salary == 1)
                                                {{ $item->rate }}%
                                            @else
                                                {{ $item->salary }}$
                                            @endif
                                        </td>
                                        <td>@if ($item->type_salary == 1)
                                            {{ $item->totel_salary }}
                                        @else
                                            {{ $item->salary }}
                                        @endif</td>
                                        <td>{{ $item->dept }}</td>
                                        <td><button class="btn btn-danger edit" data-id="{{ $item->id }}"><i
                                                    class="fa fa-edit"></i></button></td>
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
                    <h6 class="modal-title">اضافة بيانات راتب موظف</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="text-danger error" id="error"></div>
                    <form id="form-salary">
                        @csrf
                        <div class="form-group">
                            <label for="">الموظف</label>
                            <select class="form-control" name="user_id">
                                <option value="">اختر المستخدم</option>
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger error" id="error-user_id"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">نوع الراتب</label>
                                    <select class="form-control" name="type_salary">
                                        <option value="">اختر نوع الراتب</option>
                                        <option value="1">النسبة</option>
                                        <option value="2">راتب محدد</option>
                                    </select>
                                </div>
                                <div class="text-danger error" id="error-type_salary"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">قيمة النسبة / الراتب</label>
                                    <input type="number" step="any" name="salary" class="form-control"
                                        placeholder="قيمة النسبة / الراتب">
                                </div>
                                <div class="text-danger error" id="error-salary"></div>
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

    <div class="modal" id="edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">اضافة بيانات راتب موظف</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="text-danger error" id="error"></div>
                    <form id="form-salary-edit">
                        @csrf
                        <div class="form-group">
                            <label for="">الموظف</label>
                            <select class="form-control" disabled id="user_id">
                                <option value="">اختر المستخدم</option>
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="user_id" id="user_id_h">
                            <input type="hidden" name="id" id="id">
                            <div class="text-danger error" id="error-user_id-edit"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">نوع الراتب</label>
                                    <select class="form-control" name="type_salary" id="type_salary">
                                        <option value="">اختر نوع الراتب</option>
                                        <option value="1">النسبة</option>
                                        <option value="2">راتب محدد</option>
                                    </select>
                                </div>
                                <div class="text-danger error" id="error-type_salary-edit"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">قيمة النسبة / الراتب</label>
                                    <input type="number" step="any" name="salary" id="salary"
                                        class="form-control" placeholder="قيمة النسبة / الراتب">
                                </div>
                                <div class="text-danger error" id="error-salary-edit"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" id="update-salary" type="button">
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
            $("#form-salary-edit,#form-salary").submit(function(e){
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
            $("#save_salary").click(function() {
                $(".error").text("")
                moveButton($("#save_salary .sp"), $("#save_salary .text"))
                axios.post("/salary/save", $("#form-salary").serialize()).then((res) => {
                    if (res.data.error == 0) {
                        location.reload()

                    } else {
                        $("#error").text("لايمكن ادخال المستخدم مرتين")
                        stopButton($("#save_salary .sp"), $("#save_salary .text"))
                    }
                }).catch((res) => {
                    data = res.response.data.errors
                    $("#error-user_id").text(data.user_id)
                    $("#error-type_salary").text(data.typr_salary)
                    $("#error-salary").text(data.salary)
                    stopButton($("#save_salary .sp"), $("#save_salary .text"))
                })
            })

            $(".edit").click(function(){
                var id = $(this).data('id')
                axios.get(`/salary/get/${id}`).then((res)=>{
                    data = res.data
                    $("#user_id,#user_id_h").val(data.user_id)
                    $("#salary").val((data.type_salary == 1)?data.rate:data.salary)
                    $("#type_salary").val(data.type_salary)
                    $("#id").val(data.id)
                    $("#edit").modal("show")
                })
            })

            $("#update-salary").click(function(){
                $(".error").text("")
                moveButton($("#update_salary .sp"), $("#update_salary .text"))
                axios.post('/salary/update',$("#form-salary-edit").serialize()).then((res)=>{
                    location.reload()
                }).catch((res)=>{
                    console.log(res)
                    stopButton($("#update-salary .sp"), $("#update-salary .text"))
                    var data = res.response.data.errors
                    $("#error-type_salary-edit").text(data.typr_salary)
                    $("#error-salary-edit").text(data.salary)
                })
            })
        })
    </script>
@endsection
