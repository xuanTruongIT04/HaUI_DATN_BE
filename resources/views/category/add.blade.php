@extends('layouts.admin')

<link rel="stylesheet" href="{{ url('/rsrc/dist/css/auth/edit-profile.css') }}">

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Thêm mới danh mục</h3>
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
                        <h3 class="card-title">Thông tin danh mục</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="form-upload" action="{{ url('category/store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="title" class="fw-550">Tiêu đề</label>
                                <input class="form-control" type="text" name="title" id="title"
                                    value="{{ Old('title') }}" />
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group col-2 p-0">
                                <label for="level" class="fw-550">Cấp độ</i></label>
                                <input type="number" class="form-control" id="level" name="level" min="0"
                                    max="1" value="{{ Old('level', 0) }}" />
                                @error('level')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group col-2 p-0">
                                <label for="type" class="fw-550">Kiểu danh mục</i></label>
                                @php
                                    echo templateCategoryType();
                                @endphp

                                @error('type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Danh mục cha --}}
                            <div class="form-group col-10 p-0">
                                <label for="parent-id" class="fw-550">Danh mục cha</label>

                                @php
                                    echo templateCategoryParent($categories);
                                @endphp

                                @error('parent_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <input type="submit" name="btn_update" class="btn btn-primary mt-3" value="Thêm mới">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
