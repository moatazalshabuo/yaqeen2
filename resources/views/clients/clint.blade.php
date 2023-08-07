@extends('layouts.master')
@section('title')
    حساب زبون
@endsection
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->

    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">كشف حساب زبون </h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">


        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('search_clint') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <label>زبون</label>
                                <select class="form-control sel" name="client">
                                    <option value="">اختر الزبون</option>
                                    @foreach ($product as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} {{ $item->phone }}</option>
                                    @endforeach
                                </select>
                                @error('client')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                                <div class="form-group m-0 p-0">
                                    <label>من :</label>
                                    <input type="datetime-local" class="form-control" name="from">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-0 p-0">
                                    <label>الى :</label>
                                    <input type="datetime-local" class="form-control" name="to">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <input type="submit" class="btn btn-primary" value="بحث">
                            </div>
                        </div>
                    </form>
                </div>
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                <div class="card-body" style="height: 350px;overflow-y:scroll">
                    <div class="table-responsive">
                        <table class="table mg-b-0 text-md-nowrap text-left">
                            <thead>
                                <tr>
                                    <td>رقم الفاتورة</td>
                                    <td>النوع</td>
                                    <td>القمية</td>
                                    <td>الخالص</td>
                                    <td>المتبقي</td>
                                    <td>التاريخ</td>
                                    <td>مستخدم</td>
                                    <td>التحكم</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->get('data'))
                                    @foreach (session()->get('data') as $item)
                                        <tr>
                                            <td>
                                                @if ($item['type_n'] == '2')
                                                    <a href="{{ route('salesbill', $item['id_bill']) }}"> فاتورة المبيعات
                                                        رقم{{ $item['id_bill'] }} </a>
                                                @else
                                                    رقم ايصال الصرف {{ $item['id_bill'] }}
                                                @endif
                                            </td>
                                            <td>{{ $item['type'] }}</td>
                                            <td>
                                                {{ floatval($item['price']) }}
                                            </td>
                                            <td>
                                                @if ($item['type_n'] == '2')
                                                    {{ floatval($item['sincere']) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item['type_n'] == '2')
                                                    {{ floatval($item['Residual']) }}
                                                @endif
                                            </td>
                                            <td>{{ $item['created_at'] }}</td>
                                            <td>{{ $item['username'] }}</td>
                                            <td>
                                                @can('حذف ايصال')
                                                    @if ($item['type_n'] == '1')
                                                        <button class="btn btn-danger dele" id="{{ $item['id_bill'] }}"><i
                                                                class='mdi mdi-delete'></i></button>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-pink-gradient">خالص : <span>
                            @if (session()->get('sincere_s'))
                                {{ session()->get('sincere_s') }}
                            @endif
                        </span></button>
                    <button class="btn btn-pink-gradient">متبقي عليه : <span>
                            @if (session()->get('Residual_s'))
                                {{ session()->get('Residual_s') }}
                            @endif
                        </span></button>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
@endsection
