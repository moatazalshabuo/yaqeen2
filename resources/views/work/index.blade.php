@extends('layouts.master')
@section('title')
    بداء امر عمل
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
                <h4 class="content-title mb-0 my-auto">امر عمل </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">فاتورة رقم
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
        @foreach ($salesitem as $item)
            <div class="col-xl-12">
                <div class="card mg-b-20">
                    <div class="crad-header p-3">
                        <p class="card-title"> {{ $item->name }} - <span> العدد {{ $item->count }} </span> -
                            <span>{{ $item->descripe }}</span>
                        </p>
                        <div class="float-left">
                            @if ($item->status == 0)
                                <button id="{{$item->id}}" class="btn btn-success m-2 active-work">
                                    بداء امر العمل
                                </button>
                            @endif
                            @if ($item->status == 1)
                                {{-- <a href="" class="btn btn-danger m-2">
                                    ايقاف امر العمل
                                </a> --}}
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($faces as $item_face)
                                @if ($item->id == $item_face->Item_id)
                                    <div class="col-lg-6">
                                        <div class="card card-aside custom-card">
                                            <div class="card-body d-flex flex-column">
                                                <h4><a class="card-title">{{ $item_face->title }} -
                                                        @if ($item_face->height != null)
                                                            {{ $item_face->height }}*{{ $item_face->width }}
                                                        @else
                                                            {{ $item_face->quantity }}
                                                        @endif
                                                    </a></h4>
                                                <div class="">
                                                    @foreach ($material as $item_mate)
                                                        @if ($item_mate->item_face_id == $item_face->id)
                                                            <span>{{ $item_mate->material_name }} - </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div class="">العملية الاولى - العملية الثانية - العملية الثالثة</div>
                                                <div class="d-flex align-items-center pt-3 mt-auto">
                                                    <div>
                                                        <a class="text-default">الكمية : @if ($item_face->height != null)
                                                                {{ $item_face->height }}*{{ $item_face->width }}
                                                            @else
                                                                {{ $item_face->quantity }}
                                                            @endif </a>
                                                        <small class="d-block text-muted">العدد :
                                                            {{ $item_face->count }}</small>
                                                    </div>
                                                    <div class="mr-auto text-muted">
                                                        <a class="icon d-none d-md-inline-block ml-3"><i
                                                                class="far fa-heart mr-1"></i></a>
                                                        <a class="icon d-none d-md-inline-block ml-3"><i
                                                                class="far fa-thumbs-up"></i></a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="col-md-6">
                                <p>المرفقات</p>
                                <div class="row">
                                    @foreach ($files as $file)
                                        @if ($file->sales_id == $item->id)
                                            <div class="col-md">
                                                <a class="btn btn-outline-primary mt-1"
                                                    href="/uploads/{{ $item->id }}/{{ $file->file_name }}">
                                                    <i class="far fa-file-alt"></i>
                                                    <span>{{ $file->file_name }}</span>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-2">
                        <div class="row">
                            <div class="col-md-2">
                                <button class="btn btn-primary user-model" data-salesitem="{{ $item->id }}"
                                    data-toggle="modal" data-target="#users_move"><i class="fa fa-user"></i></button>
                            </div>
                            <form class="col-md-4" action="{{ route('work.files') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <div class="col-md-8">
                                        <div class="custom-file">
                                            <input class="custom-file-input" name="file[]" multiple type="file"> <label
                                                class="custom-file-label" for="customFile">تحميل مرفقات</label>
                                            @error('file')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="submit" class="btn btn-primary" value="حفظ المرفقات">
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Basic modal -->
    <div class="modal" id="users_move">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">المستخدمين المسؤولين عن المنتج</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="text-danger" id="error-massage" ></div>
                    <form class="m-2" id="form_work">
                        @csrf
                        <input type="hidden" name="salesitem" id="salesitem">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="select2 form-control" id="select-user" name="user">
                                    </select>
                                </div>
                                <div class="error text-danger" id="user_error"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="order"
                                        placeholder="ترتيب المستخدم 1 , 2 , 3">
                                </div>
                                <div class="error text-danger" id="order_error"></div>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="message"
                                        placeholder="رسالة توضيحية">
                                </div>
                                <div class="error text-danger" id="message_error"></div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" id="save_work">
                                    <span class="spinner-border spinner-border-sm sp" style="display: none">
                                    </span>
                                    <span class="text">حفظ</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <ul class="list-group" id="list-user" style="max-height: 300px;overflow-y:scroll;">
                        <li class="list-group-item"></li>
                    </ul>
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
    @include('work/ajax')
@endsection
