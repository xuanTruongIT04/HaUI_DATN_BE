@extends('layouts.admin')

<link rel="stylesheet" href="{{ url('/rsrc/dist/css/auth/edit-profile.css') }}">

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Thêm mới quản trị viên</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?">Admin</a></li>
                        <li class="breadcrumb-item active">Create</li>
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
                        <h3 class="card-title">Thông tin quản trị viên</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="form-upload" action="{{ url('admin/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name" class="fw-550">Họ và tên</label>
                                        <input class="form-control" type="text" name="name" id="name"
                                            value="{{ Old('name') }}" />
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="fw-550">Email</label>
                                        <input class="form-control" type="email" name="email" id="email"
                                            value="{{ Old('email') }}" />
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="fw-550">Mật khẩu</label>
                                        <input class="form-control" type="password" name="password" id="password"
                                            value="{{ Old('password') }}" />
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm-password" class="fw-550">Xác nhận mật khẩu</label>
                                        <input class="form-control" type="password" name="password_confirmation"
                                            id="confirm-password" value="{{ Old('password_confirmation') }}" />
                                        @error('password_confirmation')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group col-4 p-0">
                                        <label for="role" class="fw-550">Quyền <i>(Roles)</i></label>
                                        @php
                                            echo templateRoleAdmin();
                                        @endphp

                                        @error('role')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <input type="submit" name="btn_update" class="btn btn-primary mt-3" value="Thêm mới">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
