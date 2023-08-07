@extends('layouts.master')
@section('title')
	حسابات
@endsection
@section('css')
@endsection
@section('page-header')
				<!-- breadcrumb -->

				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">كشف حساب كامل </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
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
						<div class="card " dir="rtl">
							<div class="card-header">
								<form action="{{ route("account") }}" method="get">
									{{-- @csrf --}}
									<div class="row">
										<div class="col-md-5">
											<label>الموردين</label>
											<select class="form-control" name="account">
												<option value="">اختر نوع الحسابات</option>
													<option value="2">الموردين</option>
													<option value="1">الزباين</option>
											</select>
											@error('costum')
												<div class="text-danger">{{ $message }}</div>
											@enderror
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
							<div class="card-body p-3" style="height: 350px;overflow-y:scroll">
								<div class="table-responsive">
									<table class="table mg-b-0 text-md-nowrap ">
										<thead>
											<tr>
												<td>الاسم</td>
												<td>رقم الهاتف</td>
												<td>الخالص</td>
												<td>المتبقي</td>

											</tr>
										</thead>
										<tbody>

												@foreach ($data as $item)
													<tr>

														<td>{{ $item['name'] }}</td>
														<td>
															{{ $item['phone'] }}
														</td>

														<td>
															{{ $item['sincere']}}
														</td>
														<td @if ($item['Residual'] > 0) class="text-danger" @endif>
															{{ $item['Residual']}}
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
				<!-- /row -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
@endsection
