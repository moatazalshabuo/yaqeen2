@extends('layouts.master')
@section('title')
    ادارة الادوات
@endsection
@section('css')
    <link href="{{ URL::asset('assets/plugins/accordion/accordion.css') }}" rel="stylesheet" />
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
                <h4 class="content-title mb-0 my-auto">CNC</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ ادارة الدوات
                </span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" id="refresh" class="btn btn-danger btn-icon ml-2">
                    <i class="mdi mdi-refresh"></i>
                </button>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="panel-group1" id="accordion11">
        <div class="panel panel-default  mb-4">
            <div class="panel-heading1 bg-primary ">
                <h4 class="panel-title1">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion11"
                        href="#collapseFour1" aria-expanded="false">اضافة اداء<i class="fe fe-arrow-left ml-2"></i></a>
                </h4>
            </div>
            <div id="collapseFour1" class="panel-collapse collapse" role="tabpanel" aria-expanded="false" style="">
                <div class="panel-body border">
                    <div class="card">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                اضافة اداء
                            </div>
                            <p class="mg-b-20">الادوات التي يتم العمل بها في معمل cnc.</p>
                            <form action="{{ route('cnc-tools.store') }}" method="POST">
                                @csrf
                                <div class="row row-sm">
                                    <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                                        <div class="input-group">
                                            <input class="form-control" placeholder="اسم الاداء" value="{{ old('name') }}"
                                                name="name" type="text"> <span class="input-group-btn"><button
                                                    class="btn btn-primary" type="submit"><span class="input-group-btn"><i
                                                            class="fa fa-plus"></i></span></button></span>
                                        </div><!-- input-group -->
                                        <div class="text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <input type="text" id="myInput" class="form-control" placeholder="البحث با اسم الاداة">
                </div>
                <div class="card-body" style="height:400px;overflow-y: scroll;">
                    <div class="table-responsive">

                        <table class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col" class="border-bottom-0">ر.ت</th>
                                    <th scope="col" class="border-bottom-0">اسم الاداة</th>
                                    <th scope="col">المستخدم</th>
                                    <th scope="col">الحالة</th>
                                    <th scope="col" class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($tools as $item)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->created_by }}</td>
                                        <td>
                                            @if ($item->status)
                                                <span class="badge bg-success text-white">مفعل</span>
                                            @else
                                                <span class="badge bg-danger text-white">غير مفعل</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('cnc-tools.destroy', $item->id) }}" method="POST" style="display: inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button class="btn btn-danger">حذف</button>
                                            </form>
                                            <button class="btn btn-warning edit" data-id="{{ $item->id }}"
                                                data-effect="effect-scale" data-toggle="modal"
                                                data-target="#modaldemo1">تعديل</button>
                                            @if ($item->status)
                                                <a href="{{ route('unactive',$item->id) }}" class="btn btn-primary">الغاء تفعيل</a>
                                            @else
                                                <a href="{{ route('active',$item->id) }}" class="btn btn-success">تفعيل</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic modal -->
    <div class="modal" id="modaldemo1">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">تعديل الاداء</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="form-edit">
                        @csrf
                        <div class="form-group">
                            <label>اسم الاداء</label>
                            <input type="text" name="name" id="name-edit" class="form-control">
                            <input type="hidden" id="id-edit" name="id">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary " id="update" type="button">حفظ التعديل</button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">الغاء</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Basic modal -->
@endsection

@section('js')
    <script src="{{ URL::asset('assets/js/axois.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/accordion.js') }}"></script>

    <script>
        $(function() {
            $("#form-edit").submit(function(e) {
            e.preventDefault();
        });
            $(".edit").click(function() {
                $.ajax({
                    url: "{{ route('cnc-tools.show', '') }}/" + $(this).data('id'),
                    type: 'get',
                    success: function(res) {
                        $("#name-edit").val(res.name);
                        $("#id-edit").val(res.id)
                    }
                })
            })
        function update(){
            $.ajax({
                    url: "{{ route('tool.edit') }}",
                    type: "post",
                    data: $("#form-edit").serialize(),
                    success: function(res) {
                        $("#modaldemo1").modal('hide')
                        Swal.fire(
                            ' حفظ !',
                            'تمت العملية بنجاح',
                            'success'
                        ).then((resutl)=>{
                            location.reload()
                        })
                    },
                    error: function(res) {

                    }
                })
        }
            $("#update").click(function() {
                update()
            })
        })
    </script>
@endsection
