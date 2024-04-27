@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Cập nhật thông tin người dùng</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?">User</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                        <h3 class="card-title">Thông tin người dùng</h3>
                    </div>
                    <div class="card-body px-4">
                        <form method="POST" id="form-upload" action="{{ url("user/update/{$user->id}") }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-between">
                                <div class="col-6 pr-4">
                                    <div class="form-group">
                                        <label for="name" class="fw-550">Họ người dùng</label>
                                        <input class="form-control no-edit" readonly="readonly" type="text"
                                            name="last_name" id="last_name"
                                            value="{{ $user->last_name ?? Old('last_name') }}" />
                                        @error('last_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="fw-550">Tên người dùng</label>
                                        <input class="form-control no-edit" readonly="readonly" type="text"
                                            name="first_name" id="first_name"
                                            value="{{ $user->first_name ?? Old('first_name') }}" />
                                        @error('first_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="fw-550">Tên tài khoản</label>
                                        <input class="form-control no-edit" readonly="readonly" type="text"
                                            name="username" id="username"
                                            value="{{ $user->username ?? Old('username') }}" />
                                        @error('username')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="fw-550">Địa chỉ email</label>
                                        <input class="form-control no-edit" readonly="readonly" type="text"
                                            name="email" id="email" value="{{ $user->email ?? Old('email') }}" />
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name" class="fw-550">Số điện thoại</label>
                                        <input class="form-control no-edit" readonly="readonly" type="text"
                                            name="phone" id="phone" value="{{ $user->phone ?? Old('phone') }}" />
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="fw-550">Số FAX</label>
                                        <input class="form-control no-edit" readonly="readonly" type="text"
                                            name="fax" id="fax" value="{{ $user->fax ?? Old('fax') }}" />
                                        @error('fax')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="fw-550">Tổng đơn</label>
                                        <input class="form-control no-edit" readonly="readonly" type="text"
                                            name="total_order" id="total_order"
                                            value="{{ $user->total_order ?? Old('total_order') }}" />
                                        @error('total_order')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group col-8 p-0">
                                        <label for="status" class="fw-550">Trạng thái</i></label>
                                        @php
                                            echo templateUpdateStatusUser($user->status);
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
