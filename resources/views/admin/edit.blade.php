@extends('layouts.admin')

<link rel="stylesheet" href="{{ url('/rsrc/dist/css/auth/edit-profile.css') }}">

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Cập nhật thông tin quản trị viên</h3>
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
                        <h3 class="card-title">Thông tin quản trị viên</h3>
                    </div>
                    <div class="card-body px-4">
                        <form method="POST" id="form-upload" action="{{ url("admin/update/{$admin->id}") }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="fw-550">Họ và tên</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ $admin->name ?? Old('name') }}" />
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gender" class="fw-550">Giới tính</label> <BR>
                                @php
                                    $locateCheckedFemale = $admin->gender == $genders[0] || Old('gender') == $genders[0] ? 'checked' : '';
                                    $locateCheckedMale = $admin->gender == $genders[1] || Old('gender') == $genders[1] ? 'checked' : '';
                                    $locateCheckedOther = $admin->gender == $genders[2] || Old('gender') == $genders[2] ? 'checked' : '';
                                @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female"
                                        {{ $locateCheckedFemale }} value="{{ $genders[0] }}">
                                    <label class="form-check-label" for="female">
                                        Nữ
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="male"
                                        {{ $locateCheckedMale }} value="{{ $genders[1] }}">
                                    <label class="form-check-label" for="male">
                                        Nam
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="other"
                                        {{ $locateCheckedOther }} value="{{ $genders[2] }}">
                                    <label class="form-check-label" for="other">
                                        Giới tính khác
                                    </label>
                                </div>

                                @error('gender')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ $admin->address ?? Old('address') }}"
                                    placeholder="Số nhà, Ngõ, Đường, Phường, Quận, ..." />
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group col-3 p-0">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ $admin->phone ?? Old('phone') }}" placeholder="0123 xxx 456" />
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group col-5 p-0">
                                <label for="email" class="fw-550">Email</label>
                                <input class="form-control" type="email" name="email" id="email"
                                    value="{{ $admin->email }}" disabled />
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            @php
                                $url = isset($admin->avatar) ? $admin->avatar : '/rsrc/dist/img/credit/avatar-default.jpeg';
                            @endphp
                            <div class="form-group">
                                <label for='avatar' class="fw-550">Ảnh đại diện</label> <BR>
                                <div id="uploadFile">
                                    <input type="file" name="avatar" class="form-control-file upload_file"
                                        id="avatar" onchange="upload_image(this)">

                                    <img src="{{ url($url) }}" id="image_upload_file">
                                </div>
                                @error('avatar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            @php
                                $allowedRoleSuperAdmin = ['super'];
                            @endphp
                            @if (in_array(Auth::user()->role, $allowedRoleSuperAdmin) && Auth::user()->id !== $admin->id)
                                <div class="form-group col-4 p-0">
                                    <label for="role" class="fw-550">Quyền <i>(Roles)</i></label>
                                    @php
                                        echo templateRoleAdmin($admin->role);
                                    @endphp

                                    @error('role')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            <div class="form-group col-4 p-0">
                                <label for="role" class="fw-550">Trạng thái</label>
                                @php
                                    echo templateUpdateStatusAdmin($admin->status);
                                @endphp

                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @endif

                            <input type="submit" name="btn_update" class="btn btn-primary mt-3" value="Cập nhật">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
