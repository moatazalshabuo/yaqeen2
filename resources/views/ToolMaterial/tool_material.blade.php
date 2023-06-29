@extends('layouts.master')
@section('title')
    خدمات الادوات
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
                    <a class="accordion-toggle @if (!$errors->any()) collapsed @endif" data-toggle="collapse"
                        data-parent="#accordion11" href="#collapseFour1" aria-expanded="false">اضافة اداء<i
                            class="fe fe-arrow-left ml-2"></i></a>
                </h4>
            </div>
            <div id="collapseFour1" class="panel-collapse collapse  @if ($errors->any()) show @endif"
                role="tabpanel" aria-expanded="false" style="">
                <div class="panel-body border">
                    <div class="card">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                اضافة عملية
                            </div>
                            <p class="mg-b-20">الادوات التي يتم العمل بها في معمل cnc تحديد عملياتها على المواد.</p>
                            @can('اضافة cnc')
                                <form action="{{ route('toolMaterial.store') }}" method="POST">
                                    @csrf
                                    <div class="row row-sm">
                                        <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                            <div class="input-group">
                                                <input class="form-control m-1" placeholder="عنوان العملية"
                                                    value="{{ old('title') }}" name="title" type="text">
                                                <label>للنظام</label>
                                                <input type="checkbox" name="type" value="1">
                                            </div><!-- input-group -->

                                            <div class="text-danger">
                                                @error('title')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                            <div class="input-group">
                                                <select class="select2 " name="material">
                                                    <option value="">اختر المادة</option>
                                                    @foreach ($material as $item)
                                                        <option @selected(old('material') == $item->id) value="{{ $item->id }}">
                                                            {{ $item->material_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div><!-- input-group -->

                                            <div class="text-danger">
                                                @error('material')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                            <div class="input-group">
                                                <select class="select2 " name="tool">
                                                    <option value="">اختر الاداء</option>
                                                    @foreach ($tools as $item)
                                                        <option @selected(old('tool') == $item->id) value="{{ $item->id }}">
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div><!-- input-group -->

                                            <div class="text-danger">
                                                @error('tools')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                            <div class="input-group">
                                                <input class="form-control" placeholder="سعر المتر / القطعة"
                                                    value="{{ old('price') }}" name="price" type="number" step="any">
                                                <span class="input-group-btn"><button class="btn btn-primary"
                                                        type="submit"><span class="input-group-btn"><i
                                                                class="fa fa-plus"></i></span></button></span>
                                            </div><!-- input-group -->
                                            <div class="text-danger">
                                                @error('price')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endcan
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
                                    <th scope="col" class="border-bottom-0">العنوان</th>
                                    <th scope="col" class="border-bottom-0">المادة</th>
                                    <th scope="col" class="border-bottom-0">الاداة</th>
                                    <th scope="col" class="border-bottom-0">خاص بي</th>
                                    <th scope="col">المستخدم</th>
                                    <th scope="col">الحالة</th>
                                    <th scope="col" class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($toolMaterials as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->material_name }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @if ($item->type == 1)
                                                عمليات النظام
                                            @else
                                                عمليات خارجية
                                            @endif
                                        </td>
                                        <td>{{ $item->created_by }}</td>
                                        <td>
                                            @if ($item->status)
                                                <span class="badge bg-success text-white">مفعل</span>
                                            @else
                                                <span class="badge bg-danger text-white">غير مفعل</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('حذف cnc')
                                            <form action="{{ route('toolMaterial.destroy', $item->id) }}" method="POST"
                                                style="display: inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button class="btn btn-danger btn-sm">حذف</button>
                                            </form>
                                            @endcan
                                            @can("تعديل cnc")
                                            <button class="btn btn-warning btn-sm edit" data-id="{{ $item->id }}"
                                                data-effect="effect-scale" data-toggle="modal"
                                                data-target="#modaldemo1">تعديل</button>
                                            @endcan
                                            @if ($item->status)
                                                <a href="{{ route('tool.material.unactive', $item->id) }}"
                                                    class="btn btn-primary btn-sm">الغاء تفعيل</a>
                                            @else
                                                <a href="{{ route('tool.material.active', $item->id) }}"
                                                    class="btn btn-success btn-sm">تفعيل</a>
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
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>العنوان</label>
                                    <input type="text" name="title" id="title" class="form-control">
                                    <input type="hidden" id="id-edit" name="id">
                                    <label>للنظام</label>
                                    <input type="radio" id="type1" name="type" value="1">
                                    <label>خارجي</label>
                                    <input type="radio" id="type2" name="type" value="0">
                                </div>
                                <div class="col-md-12 text-danger error" id="tool-error"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label>المادة</label>
                                    <select class="" id="material" name="material">
                                        <option value="">اختر المادة</option>
                                        @foreach ($material as $item)
                                            <option value="{{ $item->id }}">{{ $item->material_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 text-danger error" id="material-error"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label>الاداء</label>
                                    <select class="" id="tool" name="tool">
                                        <option value="">اختر الاداء</option>
                                        @foreach ($tools as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 text-danger error" id="tool-error"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input class="form-control" placeholder="سعر المتر / القطعة" id="price"
                                        name="price" type="number" step="any">
                                </div>
                                <div class="col-md-12 text-danger error" id="price-error"></div>
                            </div>
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

            $("#material,#tool").select2({
                dropdownParent: $('#modaldemo1'),

            })
            $("#form-edit").submit(function(e) {
                e.preventDefault();
            });
            $(".edit").click(function() {
                $.ajax({
                    url: "{{ route('toolMaterial.show', '') }}/" + $(this).data('id'),
                    type: 'get',
                    success: function(res) {
                        $("#title").val(res.title);
                        $("#id-edit").val(res.id)
                        $("#price").val(res.price)
                        $("#material").val(res.material).change()
                        $("#tool").val(res.tool).change()
                        if (res.type == 1)
                            $("#type1").attr('checked', 'checked')
                        else
                            $("#type2").attr('checked', 'checked')

                    }
                }).catch((res)=>{
                    if(res.response.status == 403){
                        Swal.fire({
                            icon: 'error',
                            title: 'للاسف...',
                            text: 'غير مصرح لك للقيام بالعملية',
                            })
                    }
                })
            })

            function update() {
                $(".error").text("")
                $.ajax({
                    url: "{{ route('tool.material.edit') }}",
                    type: "post",
                    data: $("#form-edit").serialize(),
                    success: function(res) {
                        $("#modaldemo1").modal('hide')
                        Swal.fire(
                            ' حفظ !',
                            'تمت العملية بنجاح',
                            'success'
                        ).then((resutl) => {
                            location.reload()
                        })
                    },

                    error: function(res) {
                        if(res.response.status == 403){
                        Swal.fire({
                            icon: 'error',
                            title: 'للاسف...',
                            text: 'غير مصرح لك للقيام بالعملية',
                            })
                    }
                        data = res.responseJSON.errors
                        $("#tool-error").text(`${data.tool}`)
                        $("#material-error").text(`${data.material}`)
                        $("#price-error").text(`${data.price}`)
                        $("#title-error").text(`${data.title}`)
                    }
                })
            }
            $("#update").click(function() {
                update()
            })
        })
    </script>
@endsection
