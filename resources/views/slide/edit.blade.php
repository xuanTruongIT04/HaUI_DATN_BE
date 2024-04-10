@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Cập nhật thông tin slide</h3>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="?">Slide</a></li>
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
                    <h3 class="card-title">Thông tin slide</h3>
                </div>
                <div class="card-body px-4">
                    <form method="POST" id="form-upload" action="{{ url("slide/update/{$slide->id}") }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="fw-550">Tên slide</label>
                            <input class="form-control" type="text" name="name" id="name"
                                value="{{ $slide->name ?? Old('name') }}" />
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="fw-550">Mô tả</label>
                            <input class="form-control" type="text" name="description" id="description"
                                value="{{ $slide->description ?? Old('description') }}" />
                            @error('description')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        @php
                        $url = isset($slide->link) ? $slide->link : '/rsrc/dist/img/credit/slide-default.jpeg';
                        @endphp
                        <div class="form-group">
                            <label for='image' class="fw-550">Hình ảnh slide</label> <BR>
                            <div id="uploadFile">
                                <input type="file" name="thumb" class="form-control-file upload_file" id="image"
                                    onchange="upload_image(this)">

                                <img src={{ url($url) }} id="image_upload_file"
                                    style="width: 500px; height: 250px; margin-top: 12px;">
                            </div>
                            @error('thumb')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group col-2 p-0">
                            <label for="level" class="fw-550">Cấp độ</i></label>
                            <input type="number" class="form-control" id="level" name="level" min="0"
                                value="{{ $slide->level ?? Old('level', 0) }}" />
                            @error('level')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group col-4 p-0">
                            <label for="status" class="fw-550">Trạng thái</i></label>
                            @php
                            echo templateUpdateStatus($slide->status);
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