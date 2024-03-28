@extends('layouts.admin')

<link rel="stylesheet" href="{{ url('/rsrc/dist/css/auth/edit-profile.css') }}">

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Cập nhật mật khẩu</h3>
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
                        <h3 class="card-title">Thông tin cá nhân</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="form-upload" action="{{ url("admin/updatePassword/{$admin->id}") }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="password-old" class="fw-550">Mật khẩu cũ</label>
                                <input class="form-control" type="password" name="passwordOld" id="password-old"
                                    value="{{ Old('passwordOld') }}" />
                                @error('passwordOld')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="fw-550">Mật khẩu mới</label>
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

                            <input type="submit" name="btn_update" class="btn btn-primary mt-3" value="Cập nhật">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
