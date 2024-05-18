@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Cập nhật thông tin sản phẩm</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?">Product</a></li>
                        <li class="breadcrumb-item active">Update</li>
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
                        <h3 class="card-title">Thông tin sản phẩm</h3>
                    </div>
                    <div class="card-body">

                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation" style="margin-right: 20px;">
                                <button class="nav-link active" id="pills-basic-infor-tab" data-toggle="pill"
                                    data-target="#pills-basic-infor" type="button" role="tab"
                                    aria-controls="pills-basic-infor" aria-selected="true">Thông tin cơ bản</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-image-tab" data-toggle="pill" data-target="#pills-image"
                                    type="button" role="tab" aria-controls="pills-image" aria-selected="false">Hình
                                    ảnh</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-basic-infor" role="tabpanel"
                                aria-labelledby="pills-basic-infor-tab">

                                <form method="POST" id="form-upload" action="{{ url("product/update/{$product->id}") }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row justify-content-between">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="code" class="fw-550">Mã sản phẩm</label>
                                                <input class="form-control" type="text" name="code" id="code"
                                                    value="{{ $product->code }}" readonly="readonly" disabled />
                                            </div>

                                            <div class="form-group">
                                                <label for="slug" class="fw-550">Đường dẫn thân thiện (Slug)</label>
                                                <input class="form-control" type="text" name="slug" id="slug"
                                                    value="{{ $product->slug }}" readonly="readonly" disabled />
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="fw-550">Tên sản phẩm</label>
                                                <input class="form-control" type="text" name="name" id="name"
                                                    value="{{ $product->name ?? Old('name') }}" />
                                                @error('name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            {{-- Danh mục sản phẩm --}}
                                            <div class="form-group p-0">
                                                <label for="category-id" class="fw-550">Danh mục sản phẩm</label>

                                                @php
                                                    echo templateCategoryProduct();
                                                @endphp

                                                @error('category_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            {{-- Danh mục sản phẩm --}}
                                            <div class="form-group p-0">
                                                <label for="brand-id" class="fw-550">Nhãn hiệu</label>

                                                @php
                                                    echo templateBrandProduct();
                                                @endphp

                                                @error('brand_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="col-5">
                                            <div class="form-group">
                                                <label for="price" class="fw-550">Giá bán</label>
                                                <input class="form-control d-block" type="text" name="price"
                                                    id="price" placeholder="Tính theo đô ($)"
                                                    value="{{ $product->price ?? Old('price') }}" />
                                                @error('price')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="discount" class="fw-550">Giảm giá (%)</label>
                                                <input class="form-control d-block" type="number" min="0"
                                                    max="100" name="discount" id="discount"
                                                    placeholder="Tính theo phần trăm (%)"
                                                    value="{{ $product->discount ?? Old('discount') }}" />
                                                @error('discount')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="qty_import" class="fw-550">Số lượng nhập</label>
                                                <input class="form-control d-block" type="number" min="0"
                                                    name="qty_import" id="qty_import"
                                                    value="{{ $product->qty_import ?? Old('qty_import') }}" />
                                                @error('qty_import')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="qty_sold" class="fw-550">Số lượng đã bán</label>
                                                <input class="form-control d-block" type="number" min="0"
                                                    name="qty_sold" id="qty_sold"
                                                    value="{{ $product->qty_sold ?? Old('qty_sold') }}" />
                                                @error('qty_sold')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="rate" class="fw-550 mr-1">Đánh giá </label>
                                                <i>(Trên thang 5 sao)</i>
                                                <input class="form-control d-block" type="number" min="0"
                                                    max="5" name="rate" id="rate"
                                                    placeholder="Trên thang 5 sao"
                                                    value="{{ $product->rate ?? Old('rate', '0') }}" />
                                                @error('rate')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row justify-content-between">
                                        <div class="col-6 ">
                                            <div class="form-group">
                                                <label for="detail" class="fw-550">Chi tiết</label>
                                                <textarea class="form-control" name="detail" id="detail">{{ $product->detail ?? Old('detail') }}</textarea>
                                                @error('detail')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="ml-5 col-5">
                                            <div class="form-group">
                                                <label for="description" class="fw-550">Mô tả</label>
                                                <textarea class="form-control col-3" name="description" id="description">{{ $product->description ?? Old('description') }}</textarea>
                                                @error('description')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <!-- Ngày sản xuất -->
                                        <div class="form-group col-5">
                                            <label for="date-of-manufacture" class="fw-550">Ngày sản xuất</label>
                                            <input type="datetime-local" class="form-control" name="date_of_manufacture" id="date-of-manufacture" value="{{  $product->date_of_manufacture ? date('Y-m-d\TH:i', strtotime($product->date_of_manufacture)) : Old('date_of_manufacture') }}">
                                            @error('date_of_manufacture')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Ngày hết hạn -->
                                        <div class="form-group col-5">
                                            <label for="expiry-date" class="fw-550">Ngày hết hạn</label>
                                            <input type="datetime-local" class="form-control" name="expiry_date" id="expiry-date" value="{{  $product->expiry_date ? date('Y-m-d\TH:i', strtotime($product->expiry_date)) : Old('expiry_date') }}">
                                            @error('expiry_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group p-0">
                                        <label for="status" class="fw-550">Trạng thái</i></label>
                                        @php
                                            echo templateUpdateStatus($product->status);
                                        @endphp

                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <input type="submit" name="btn_update" class="btn btn-primary mt-3"
                                        value="Cập nhật">
                                </form>
                            </div>


                            <div class="tab-pane fade" id="pills-image" role="tabpanel"
                                aria-labelledby="pills-image-tab">
                                <form method="POST" id="form-upload"
                                    action="{{ url("product/updateImage/{$product->id}") }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-4">
                                            {{-- Màu sắc sản phẩm --}}
                                            <div class="form-groupp-0">
                                                <label for="brand-id" class="fw-550 mr-1">Màu sắc</label>
                                                <i>(Chọn màu sắc trước)</i>
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
                                                    <input type="file" name="thumb"
                                                        class="form-control-file upload_file" id="thumb"
                                                        onchange="upload_image(this)">
                                                    <img class="mt-3" src="{{ url($url) }}" id="image_upload_file">
                                                </div>
                                                <span class="notifiExists"></span>
                                                @error('thumb')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <input type="submit" name="btn_add_image" class="btn btn-primary mt-3"
                                                value="Cập nhật">
                                        </div>

                                        <div class="ml-4 col-7">
                                            <div class="form-group">
                                                <label for='thumb' class="fw-550">Danh sách hình ảnh</label> <BR>
                                                <div id="uploadFile">
                                                    <input type="file" name="list_thumb[]" multiple=""
                                                        class="form-control-file" data-max_length="20"
                                                        id="update_multi_thumb">
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
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
