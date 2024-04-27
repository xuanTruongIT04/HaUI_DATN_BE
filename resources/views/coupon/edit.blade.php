@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Cập nhật thông tin phiếu giảm giá</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?">Admin</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- /.col (left) -->
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin phiếu giảm giá</h3>
                    </div>
                    <div class="card-body px-4">
                        <form method="POST" id="form-upload" action="{{ url("coupon/update/{$coupon->id}") }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-6 mr-4">
                                    <div class="form-group">
                                        <label for="name" class="fw-550">Tên phiếu giảm giá</label>
                                        <input class="form-control" type="text" name="name" id="name"
                                            value="{{ $coupon->name ?? Old('name') }}" />
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="code" class="fw-550">Mã phiếu giảm giá</label>
                                        <input class="form-control" type="text" name="code" id="code"
                                            value="{{ $coupon->code ?? Old('code') }}" />
                                        @error('code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="percent" class="fw-550">Phần trăm giảm giá</label>
                                        <input class="form-control" type="text" name="percent" id="percent"
                                            value="{{ $coupon->percent ?? Old('percent') }}" />
                                        @error('percent')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-5 ml-4">
                                    <div class="form-group p-0">
                                        <label for="start_date" class="fw-550">Ngày bắt đầu</label>
                                        <input type="datetime-local" class="form-control" name="start_date" id="start_date"
                                            value="{{ $coupon->start_date ? date('Y-m-d\TH:i', strtotime($coupon->start_date)) : '' }}">
                                        @error('start_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group p-0">
                                        <label for="end_date" class="fw-550">Ngày kết thúc</label>
                                        <input type="datetime-local" class="form-control" name="end_date" id="end_date"
                                            value="{{ $coupon->end_date ? date('Y-m-d\TH:i', strtotime($coupon->end_date)) : '' }}">
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group p-0">
                                        <label for="status" class="fw-550">Trạng thái</i></label>
                                        @php
                                            echo templateUpdateStatus($coupon->status);
                                        @endphp

                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <input type="submit" name="btn_update" class="btn btn-primary mt-3" value="Cập nhật">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
