@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Thêm chi tiết thẻ theo sản phẩm</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?">Tag</a></li>
                        <li class="breadcrumb-item active">Add with product</li>
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
                        <h3 class="card-title">Thông tin thẻ</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="form-upload" action="{{ url('product-tag/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">

                                <div class="form-group col-6">
                                    <label for="name" class="fw-550">Thẻ</label>
                                    {!! templateTagProduct() !!}
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="name" class="fw-550">Sản phẩm</label>
                                    {!! templateProduct() !!}
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
