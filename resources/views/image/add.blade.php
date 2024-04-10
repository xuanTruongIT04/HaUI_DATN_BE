@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    @if (!empty($productName))
                        <h3 class="m-0">Thêm ảnh cho sản phẩm <b>{{ $productName }}</b></h3>
                    @else
                        <h3 class="m-0">Thêm mới hình ảnh theo sản phẩm</h3>
                    @endif
                </div><!-- /.col -->
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?">Image</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="container-fluid" id="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- /.col (left) -->
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">Thông tin hình ảnh</h3>
                    </div>
                    <div class="card-body">
                        @if (!empty($idProduct))
                            <form method="POST" id="form-upload" action="{{ url('image/store/' . $idProduct) }}"
                                enctype="multipart/form-data">
                            @else
                                <form method="POST" id="form-upload" action="{{ url('image/store') }}"
                                    enctype="multipart/form-data">
                        @endif
                        @csrf

                        <div class="row">
                            <div class="col-4">
                                {{-- Sản phẩm --}}
                                @if (empty($idProduct))
                                    <div class="form-groupp-0">
                                        <label for="brand-id" class="fw-550">Sản phẩm</label>
                                        @php
                                            echo templateProduct();
                                        @endphp

                                        @error('product_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endif
                                {{-- Màu sắc sản phẩm --}}
                                <div class="form-groupp-0 mt-3">
                                    <label for="brand-id" class="fw-550">Màu sắc</label>
                                    @php
                                        echo templateColorProduct();
                                    @endphp

                                    @error('color_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                @php
                                    $url = isset($product->thumb) ? $product->thumb : '/rsrc/dist/img/credit/product-thumb-default.jpg';
                                @endphp
                                <div class="form-group mt-3">
                                    <label for='thumb' class="fw-550 d-block">Hình ảnh chính của sản
                                        phẩm</label>
                                    <div id="uploadFile">
                                        <input type="file" name="thumb" class="form-control-file upload_file"
                                            id="thumb" onchange="upload_image(this)">
                                        <img class="mt-3" src="{{ url($url) }}" id="image_upload_file">
                                    </div>
                                    <span class="notifiExists"></span>
                                    @error('thumb')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <input type="submit" name="btn_add_image" class="btn btn-primary mt-3" value="Thêm mới">
                            </div>

                            <div class="ml-4 col-7">
                                <div class="form-group">
                                    <label for='thumb' class="fw-550">Danh sách hình ảnh</label> <BR>
                                    <div id="uploadFile">
                                        <input type="file" name="list_thumb[]" multiple="" class="form-control-file"
                                            data-max_length="20" id="update_multi_thumb">
                                        <div class="list-image-upload-multi">
                                            <img src="/rsrc/dist/img/credit/product-thumb-default.jpg"
                                                class="img-um-default"
                                                style="margin-top: 15px;width:150px; height: 150px; border: 1px solid #000;" />
                                        </div>
                                    </div>
                                    <span class="notifiLoading"></span> <BR>
                                    @error('list_thumb')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
