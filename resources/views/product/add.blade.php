@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Thêm mới sản phẩm</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?">Product</a></li>
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
                    <div class="card-header">
                        <h3 class="card-title">Thông tin sản phẩm</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="form-upload" action="{{ url('product/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-4 mr-5">
                                    <div class="form-group">
                                        <label for="name" class="fw-550">Tên sản phẩm</label>
                                        <input class="form-control" type="text" name="name" id="name"
                                            value="{{ Old('name') }}" />
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="price" class="fw-550">Giá bán</label>
                                        <input class="form-control d-block" type="text" name="price" id="price"
                                            placeholder="Tính theo đô ($)" value="{{ Old('price') }}" />
                                        @error('price')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="discount" class="fw-550">Giảm giá (%)</label>
                                        <input class="form-control d-block" type="text" min="0" max="100"
                                            name="discount" id="discount" placeholder="Tính theo phần trăm (%)"
                                            value="{{ Old('discount') }}" />
                                        @error('discount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="qty_import" class="fw-550">Số lượng nhập</label>
                                        <input class="form-control d-block" type="number" min="0" name="qty_import"
                                            id="qty_import" value="{{ Old('qty_import') }}" />
                                        @error('qty_import')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="qty_sold" class="fw-550">Số lượng đã bán</label>
                                        <input class="form-control d-block" type="number" min="0" name="qty_sold"
                                            id="qty_sold" value="{{ Old('qty_sold') }}" />
                                        @error('qty_sold')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-7">
                                    <div class="form-group">
                                        <label for="description" class="fw-550">Mô tả</label>
                                        <textarea class="form-control" name="description" id="description">{{ Old('description') }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class=" form-group">
                                <label for="detail" class="fw-550">Chi tiết</label>
                                <textarea class="form-control" name="detail" id="detail">{{ Old('detail') }}</textarea>
                                @error('detail')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Danh mục sản phẩm -->
                            <div class="row justify-content-between">
                                <div class="form-group col-7">
                                    <label for="category-id" class="fw-550">Danh mục sản phẩm</label>

                                    @php
                                        echo templateCategoryProduct();
                                    @endphp

                                    @error('category_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Ngày sản xuất -->
                                <div class="form-group col-5">
                                    <label for="date-of-manufacture" class="fw-550">Ngày sản xuất</label>
                                    <input type="datetime-local" class="form-control" name="date_of_manufacture" id="date-of-manufacture"   value="{{ Old('date_of_manufacture') }}">
                                    @error('date_of_manufacture')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>


                            <div class="row">
                                <!-- Nhãn hiệu -->
                                <div class="form-group col-7">
                                    <label for="brand-id" class="fw-550">Nhãn hiệu</label>

                                    @php
                                        echo templateBrandProduct();
                                    @endphp

                                    @error('brand_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Ngày hết hạn -->
                                <div class="form-group col-5">
                                    <label for="expiry-date" class="fw-550">Ngày hết hạn</label>
                                    <input type="datetime-local" class="form-control" name="expiry_date" id="expiry-date" value="{{ Old('expiry_date') }}">
                                    @error('expiry_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <input type="submit" name="btn_add_basic_info" class="btn btn-primary mt-3"
                                value="Thêm mới">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
