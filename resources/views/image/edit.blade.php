@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Cập nhật thông tin hình ảnh</h3>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="?">Image</a></li>
                    <li class="breadcrumb-item active">Edit information</li>
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
                    <h3 class="card-title">Thông tin hình ảnh</h3>
                </div>
                <div class="card-body px-4">
                    <form method="POST" id="form-upload" action="{{ url("image/update/{$image->id}") }}"
                        enctype="multipart/form-data">
                        @csrf

                        @php
                        $url = isset($image->link) ? $image->link : '/rsrc/dist/img/credit/slide-default.jpeg';
                        @endphp
                        <div class="form-group">
                            <label for='image' class="fw-550" style="margin-right: 12px">Hình ảnh</label><i>(Không chỉnh
                                sửa đường dẫn)</i> <BR>
                            <div id="uploadFile">
                                <img src="{{ url($url) }}" id="image_upload_file"
                                    style="width: 250px; height: 250px; margin-top: 12px;">
                            </div>
                            @error('thumb')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description" class="fw-550">Mô tả</label>
                            <textarea class="form-control" name="description"
                                id="description">{{ $image->description ?? Old('description') }}</textarea>
                            @error('description')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        @php
                        $level = $image->level ? (string)($image->level) : "0";
                        @endphp
                        <div class="form-group">
                            <label for="level" class="fw-550">Cấp độ</label><br>
                            <input type="number" min="0" name="level" class="form-control w-30"
                                value="{{ $level }}" /><br>
                            @error('level')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group col-4 p-0">
                            <label for="status" class="fw-550">Trạng thái</i></label>
                            @php
                            echo templateUpdateStatus($image->status);
                            @endphp

                            @error('status')
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