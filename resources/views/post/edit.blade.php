@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Cập nhật thông tin bài viết</h3>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="?">Post</a></li>
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
                    <h3 class="card-title">Thông tin bài viết</h3>
                </div>
                <div class="card-body px-4">
                    <form method="POST" id="form-upload" action="{{ url("post/update/{$post->id}") }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="fw-550">Tiêu đề bài viết</label>
                            <input class="form-control" type="text" name="title" id="title"
                                value="{{ $post->title ?? Old('title') }}" />
                            @error('title')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="fw-550">Mô tả</label>
                            <textarea class="form-control col-3" name="description"
                                id="description">{{ $post->description ?? Old('description') }}</textarea>
                            @error('description')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content" class="fw-550">Nội dung chi tiết</label>
                            <textarea class="form-control" name="content"
                                id="content">{{$post->content ?? Old('content') }}</textarea>
                            @error('content')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        @php
                        $url = isset($post->link) ? $post->link :
                        '/rsrc/dist/img/credit/product-thumb-default.jpg';
                        @endphp
                        <div class="form-group">
                            <label for='thumb' class="fw-550">Hình ảnh chính của bài viết</label> <BR>
                            <div id="uploadFile">
                                <input type="file" name="thumb" class="form-control-file upload_file" id="thumb"
                                    onchange="upload_image(this)">
                                <img class="mt-2" src={{ url($url) }} id="image_upload_file"
                                    style="width: 400px; height: 280px;">
                            </div>
                            @error('thumb')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group col-4 p-0">
                            <label for="status" class="fw-550">Trạng thái</i></label>
                            @php
                            echo templateUpdateStatus($post->status);
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