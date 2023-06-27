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

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: lightgrey;
        }

        #progressbar .active {
            color: #000000;
        }

        #progressbar li {
            text-align: center;
            list-style-type: none;
            font-size: 12px;
            width: 20%;
            float: left;
            position: relative;
        }


        #progressbar li span {
            display: block;
            background-color: lightgray;
            width: 50px;
            margin: auto;
            padding: 10px;
            border-radius: 50%;
            font-size: 15px;
            color: #fff;
        }

        /*ProgressBar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: lightgray;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: -1;
        }

        /*Color number of the step and the connector before it*/
        #progressbar li.active span,
        #progressbar li.active:after {
            background: skyblue;
        }

        #progressbar li.succ span,
        #progressbar li.succ:after {
            background: rgb(123, 255, 111);
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
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
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
                <div class="crad-header p-3">
                    <p class="card-title"> {{ $item->name }} - <span> العدد {{ $item->count }} </span> -
                        <span>{{ $item->descripe }}</span>
                    </p>
                    @if ($item->user_id == Auth::id())
                        <div class="float-left">
                            @if ($work->status == 1)
                                <button data-id="{{ $work->id }}" class="start btn btn-success m-2">
                                    بداء العمل
                                </button>
                            @elseif($work->status == 2)
                                <button data-id="{{ $work->id }}" class="btn btn-danger m-2 cancel">
                                    الغاء العمل
                                </button>
                                <button data-id="{{ $work->id }}" class="btn btn-success m-2 end">
                                    تم الانتهاء
                                </button>
                            @else
                                العمل مكتمل
                            @endif
                        </div>
                    @endif
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
                            <p>الملاحظات</p>
                            <p class="px-1">{{ $work->message }}</p>
                        </div>
                        <div class="col-md-6">
                            <p>المرفقات</p>
                            <div class="row">
                                @foreach ($files as $file)
                                    <div class="col-md">
                                        <a class="btn btn-outline-primary mt-1"
                                            href="/uploads/{{ $item->id }}/{{ $file->file_name }}">
                                            <i class="far fa-file-alt"></i>
                                            <span>{{ $file->file_name }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-2">
                    <div class="row">
                        @can("اعطاء امر عمل")
                        <div class="col-md-12">
                            <ul id="progressbar">
                                @foreach ($works as $work_item)
                                    <li class="@if ($work_item->status == 2) active @elseif($work_item->status == 3) succ @endif"
                                        id="account">
                                        <span>{{ $work_item->order }}</span><strong>{{ $work_item->name }}</strong>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/js/axois.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/accordion.js') }}"></script>
    <script>
        $(".start").click(function() {
            var id = $(this).data("id")
            Swal.fire({
                title: '؟هل انت متاكد ',
                text: "من انك تريد بداء العمل!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'موافق!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.replace(`/start-work/start-work/${id}`)
                }
            })
        })

        $(".cancel").click(function() {
            var id = $(this).data("id")
            Swal.fire({
                title: '؟هل انت متاكد ',
                text: "من انك تريد الغاء العمل!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'موافق!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.replace(`/start-work/cancel-work/${id}`)
                }
            })
        })
        $(".end").click(function() {
            var id = $(this).data("id")
            Swal.fire({
                title: '؟هل انت متاكد ',
                text: "من انهيت العمل!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'موافق!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.replace(`/start-work/end-work/${id}`)
                }
            })
        })
    </script>
@endsection
