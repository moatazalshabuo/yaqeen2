{{-- @extends('layouts.app')
@section('style')
  
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Frontend/frontend.create_invoice') }}
                </div>

                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="post" class="form">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="customer_name">{{ __('Frontend/frontend.customer_name') }}</label>
                                    <input type="text" name="customer_name" class="form-control">
                                    @error('customer_name')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="customer_email">{{ __('Frontend/frontend.customer_email') }}</label>
                                    <input type="text" name="customer_email" class="form-control">
                                    @error('customer_email')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="customer_mobile">{{ __('Frontend/frontend.customer_mobile') }}</label>
                                    <input type="text" name="customer_mobile" class="form-control">
                                    @error('customer_mobile')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_name">{{ __('Frontend/frontend.company_name') }}</label>
                                    <input type="text" name="company_name" class="form-control">
                                    @error('company_name')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="invoice_number">{{ __('Frontend/frontend.invoice_number') }}</label>
                                    <input type="text" name="invoice_number" class="form-control">
                                    @error('invoice_number')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="invoice_date">{{ __('Frontend/frontend.invoice_date') }}</label>
                                    <input type="text" name="invoice_date" class="form-control pickdate">
                                    @error('invoice_date')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table" id="invoice_details">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ __('Frontend/frontend.product_name') }}</th>
                                    <th>{{ __('Frontend/frontend.unit') }}</th>
                                    <th>{{ __('Frontend/frontend.quantity') }}</th>
                                    <th>{{ __('Frontend/frontend.unit_price') }}</th>
                                    <th>{{ __('Frontend/frontend.product_subtotal') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="cloning_row" id="0">
                                    <td>#</td>
                                    <td>
                                        <input type="text" name="product_name[0]" id="product_name" class="product_name form-control">
                                        @error('product_name')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                    </td>
                                    <td>
                                        <select name="unit[0]" id="unit" class="unit form-control">
                                            <option></option>
                                            <option value="piece">{{ __('Frontend/frontend.piece') }}</option>
                                            <option value="g">{{ __('Frontend/frontend.gram') }}</option>
                                            <option value="kg">{{ __('Frontend/frontend.kilo_gram') }}</option>
                                        </select>
                                        @error('unit')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[0]" step="0.01" id="quantity" class="quantity form-control">
                                        @error('quantity')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                    </td>
                                    <td>
                                        <input type="number" name="unit_price[0]" step="0.01" id="unit_price" class="unit_price form-control">
                                        @error('unit_price')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="row_sub_total[0]" id="row_sub_total" class="row_sub_total form-control" readonly="readonly">
                                        @error('row_sub_total')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <button type="button" class="btn_add btn btn-primary">{{ __('Frontend/frontend.add_another_product') }}</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">{{ __('Frontend/frontend.sub_total') }}</td>
                                    <td><input type="number" step="0.01" name="sub_total" id="sub_total" class="sub_total form-control" readonly="readonly"></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">{{ __('Frontend/frontend.discount') }}</td>
                                    <td>
                                        <div class="input-group mb-3">
                                            <select name="discount_type" id="discount_type" class="discount_type custom-select">
                                                <option value="fixed">{{ __('Frontend/frontend.sr') }}</option>
                                                <option value="percentage">{{ __('Frontend/frontend.percentage') }}</option>
                                            </select>
                                            <div class="input-group-append">
                                                <input type="number" step="0.01" name="discount_value" id="discount_value" class="discount_value form-control" value="0.00">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">{{ __('Frontend/frontend.vat') }}</td>
                                    <td><input type="number" step="0.01" name="vat_value" id="vat_value" class="vat_value form-control" readonly="readonly"></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">{{ __('Frontend/frontend.shipping') }}</td>
                                    <td><input type="number" step="0.01" name="shipping" id="shipping" class="shipping form-control"></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">{{ __('Frontend/frontend.total_due') }}</td>
                                    <td><input type="number" step="0.01" name="total_due" id="total_due" class="total_due form-control" readonly="readonly"></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="text-right pt-3">
                            <button type="submit" name="save" class="btn btn-primary">{{ __('Frontend/frontend.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    {{-- <script src="{{ asset('frontend/js/form_validation/jquery.form.js') }}"></script>
    <script src="{{ asset('frontend/js/form_validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('frontend/js/form_validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('frontend/js/pickadate/picker.js') }}"></script>
    <script src="{{ asset('frontend/js/pickadate/picker.date.js') }}"></script>
   
    <script src="{{ asset('frontend/js/custom.js') }}"></script> --}}
{{-- @endsection --}} --}}


@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection
@section('title')
    اضافة فاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    اضافة فاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- row -->
    <div class="row">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="post" class="form" autocomplete="off">
                        {{-- <input type="hidden" id="token_search" value="{{csrf_token() }}"> --}}
                        {{-- {{ csrf_field() }} --}}
                        {{-- 1 --}}
@csrf
                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">الزبون</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    title="يرجى ادخال اسم العميل" required>
                                    @error('customer_name')<span class="help-block text-danger">{{ $message }}</span>@enderror

                    
                            </div>
                            <div class="col">
                                <label for="customer_email">البريد الالكتروني</label>
                                <input type="text" name="customer_email" class="form-control">
                                @error('customer_email')<span class="help-block text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="col">
                                <label for="customer_email">رقم الهاتف</label>
                                <input type="text" name="customer_mobile" class="form-control">
                                @error('customer_mobile')<span class="help-block text-danger">{{ $message }}</span>@enderror
                            </div>
                           
                            
                        
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="customer_email">اسم الشركة</label>
                                <input type="text" name="company_name" class="form-control">
                                @error('company_name')<span class="help-block text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="col">
                                <label for="invoice_number">رقم الفاتورة</label>
                                <input type="text" name="invoice_number	" class="form-control">
                                @error('invoice_number')<span class="help-block text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="col">
                                <label for="invoice_date">تاريخ الفاتورة</label>
                                <input type="date" name="invoice_date" class="form-control pickdate">
                                @error('invoice_date')<span class="help-block text-danger">{{ $message }}</span>@enderror
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table" id="invoice_details">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>اسم المنتج</th>
                                        <th>نوع الوحدة</th>
                                        <th>الكمية</th>
                                        <th>سعر الوحدة</th>
                                        <th>الاجمالي</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="cloning_row" id="0">
                                        <td>#</td>
                                        <td>
                                            <input type="text" name="product_name[0]" id="product_name" class="product_name form-control">
                                            @error('product_name')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                        </td>
                                        <td>
                                            <select name="unit[0]" id="unit" class="unit form-control">
                                                <option></option>
                                                <option value="piece">{{ __('Frontend/frontend.piece') }}</option>
                                                <option value="g">{{ __('Frontend/frontend.gram') }}</option>
                                                <option value="kg">{{ __('Frontend/frontend.kilo_gram') }}</option>
                                            </select>
                                            @error('unit')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                        </td>
                                        <td>
                                            <input type="number" name="quantity[0]" step="0.01" id="quantity" class="quantity form-control">
                                            @error('quantity')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                        </td>
                                        <td>
                                            <input type="number" name="unit_price[0]" step="0.01" id="unit_price" class="unit_price form-control">
                                            @error('unit_price')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="row_sub_total[0]" id="row_sub_total" class="row_sub_total form-control" readonly="readonly">
                                            @error('row_sub_total')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <button type="button" class="btn_add btn btn-primary"> حفظ منتج جديد</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="2">المجموع الفرعي</td>
                                            <td><input type="number" step="0.01" name="sub_total" id="sub_total" class="sub_total form-control" readonly="readonly"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="2">نوع التخفيض </td>
                                            <td>
                                                <div class="input-group mb-3">
                                                    <select name="discount_type" id="discount_type" class="discount_type custom-select">
                                                        <option value="fixed"></option>
                                                        <option value="percentage"></option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <input type="number" step="0.01" name="discount_value" id="discount_value" class="discount_value form-control" value="0.00">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="2">قيمة التخفيض</td>
                                            <td><input type="number" step="0.01" name="vat_value" id="vat_value" class="vat_value form-control" readonly="readonly"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="2">الشحن</td>
                                            <td><input type="number" step="0.01" name="shipping" id="shipping" class="shipping form-control"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="2">الإجمالي المستحق</td>
                                            <td><input type="number" step="0.01" name="total_due" id="total_due" class="total_due form-control" readonly="readonly"></td>
                                        </tr>
                                        </tfoot>
                                </table>
                        </div>
                        <br>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                        </div>


                    </form>
                </div>
                </div>
                </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();
    </script>



<script src="{{ asset('frontend/js/custom.js') }}"></script>

@endsection




{{-- @extends('layouts.master')
@section('css')
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">Pages</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Empty</span>
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
							<div class="btn-group dropdown">
								<button type="button" class="btn btn-primary">14 Aug 2019</button>
								<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" id="dropdownMenuDate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuDate" data-x-placement="bottom-end">
									<a class="dropdown-item" href="#">2015</a>
									<a class="dropdown-item" href="#">2016</a>
									<a class="dropdown-item" href="#">2017</a>
									<a class="dropdown-item" href="#">2018</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row">
                    @section('content')
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    {{ __('Frontend/frontend.create_invoice') }}
                                </div>
                
                                <div class="card-body">
                                    <form action="{{ route('invoices.store') }}" method="post" class="form">
                                        @csrf
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="customer_name">{{ __('Frontend/frontend.customer_name') }}</label>
                                                    <input type="text" name="customer_name" class="form-control">
                                                    @error('customer_name')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="customer_email">{{ __('Frontend/frontend.customer_email') }}</label>
                                                    <input type="text" name="customer_email" class="form-control">
                                                    @error('customer_email')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="customer_mobile">{{ __('Frontend/frontend.customer_mobile') }}</label>
                                                    <input type="text" name="customer_mobile" class="form-control">
                                                    @error('customer_mobile')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="company_name">{{ __('Frontend/frontend.company_name') }}</label>
                                                    <input type="text" name="company_name" class="form-control">
                                                    @error('company_name')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="invoice_number">{{ __('Frontend/frontend.invoice_number') }}</label>
                                                    <input type="text" name="invoice_number" class="form-control">
                                                    @error('invoice_number')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="invoice_date">{{ __('Frontend/frontend.invoice_date') }}</label>
                                                    <input type="text" name="invoice_date" class="form-control pickdate">
                                                    @error('invoice_date')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="table-responsive">
                                            <table class="table" id="invoice_details">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>{{ __('Frontend/frontend.product_name') }}</th>
                                                    <th>{{ __('Frontend/frontend.unit') }}</th>
                                                    <th>{{ __('Frontend/frontend.quantity') }}</th>
                                                    <th>{{ __('Frontend/frontend.unit_price') }}</th>
                                                    <th>{{ __('Frontend/frontend.product_subtotal') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="cloning_row" id="0">
                                                    <td>#</td>
                                                    <td>
                                                        <input type="text" name="product_name[0]" id="product_name" class="product_name form-control">
                                                        @error('product_name')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                    </td>
                                                    <td>
                                                        <select name="unit[0]" id="unit" class="unit form-control">
                                                            <option></option>
                                                            <option value="piece">{{ __('Frontend/frontend.piece') }}</option>
                                                            <option value="g">{{ __('Frontend/frontend.gram') }}</option>
                                                            <option value="kg">{{ __('Frontend/frontend.kilo_gram') }}</option>
                                                        </select>
                                                        @error('unit')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" name="quantity[0]" step="0.01" id="quantity" class="quantity form-control">
                                                        @error('quantity')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" name="unit_price[0]" step="0.01" id="unit_price" class="unit_price form-control">
                                                        @error('unit_price')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="row_sub_total[0]" id="row_sub_total" class="row_sub_total form-control" readonly="readonly">
                                                        @error('row_sub_total')<span class="help-block text-danger">{{ $message }}</span>@enderror
                                                    </td>
                                                </tr>
                                                </tbody>
                
                                                <tfoot>
                                                <tr>
                                                    <td colspan="6">
                                                        <button type="button" class="btn_add btn btn-primary">{{ __('Frontend/frontend.add_another_product') }}</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td colspan="2">{{ __('Frontend/frontend.sub_total') }}</td>
                                                    <td><input type="number" step="0.01" name="sub_total" id="sub_total" class="sub_total form-control" readonly="readonly"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td colspan="2">{{ __('Frontend/frontend.discount') }}</td>
                                                    <td>
                                                        <div class="input-group mb-3">
                                                            <select name="discount_type" id="discount_type" class="discount_type custom-select">
                                                                <option value="fixed">{{ __('Frontend/frontend.sr') }}</option>
                                                                <option value="percentage">{{ __('Frontend/frontend.percentage') }}</option>
                                                            </select>
                                                            <div class="input-group-append">
                                                                <input type="number" step="0.01" name="discount_value" id="discount_value" class="discount_value form-control" value="0.00">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td colspan="2">{{ __('Frontend/frontend.vat') }}</td>
                                                    <td><input type="number" step="0.01" name="vat_value" id="vat_value" class="vat_value form-control" readonly="readonly"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td colspan="2">{{ __('Frontend/frontend.shipping') }}</td>
                                                    <td><input type="number" step="0.01" name="shipping" id="shipping" class="shipping form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td colspan="2">{{ __('Frontend/frontend.total_due') }}</td>
                                                    <td><input type="number" step="0.01" name="total_due" id="total_due" class="total_due form-control" readonly="readonly"></td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                
                                        <div class="text-right pt-3">
                                            <button type="submit" name="save" class="btn btn-primary">{{ __('Frontend/frontend.save') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                
                
                @endsection
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
@endsection --}}