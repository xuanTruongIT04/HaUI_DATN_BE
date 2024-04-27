@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Cập nhật thông tin giỏ hàng</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?">Cart</a></li>
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
                        <h3 class="card-title">Thông tin giỏ hàng</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="form-upload" action="{{ url("cart/update/{$cart->id}") }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-2">
                                <div class="col-12">
                                    <div class="row pb-3">
                                        <div class="col-6 pr-4">
                                            <div class="form-group">
                                                <label for="last-name">Họ khách hàng</label>
                                                <input class="form-control no-edit" type="text" name="code readonly"
                                                    id="last-name" readonly="readonly" value="{{ $cart->user->last_name }}">
                                                @error('last_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="first-name">Tên khách hàng</label>
                                                <input class="form-control no-edit" type="text" name="code readonly"
                                                    id="first-name" readonly="readonly"
                                                    value="{{ $cart->user->first_name }}">
                                                @error('first_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="email">Email khách hàng</label>
                                                <input class="form-control no-edit" type="email" name="code readonly"
                                                    id="email" readonly="readonly" value="{{ $cart->user->email }}">
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="phone">Số điện thoại</label>
                                                <input class="form-control no-edit" type="text" name="code readonly"
                                                    id="phone" readonly="readonly" value="{{ $cart->user->phone }}">
                                                @error('phone')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header font-weight-bold">
                                            Sản phẩm giỏ hàng
                                        </div>
                                        <div class="card-body" id="information-order">
                                            @if (count($listDetailCart) > 0)
                                                <table class="table table-striped table-checkall">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Ảnh sản phẩm</th>
                                                            <th scope="col">Tên sản phẩm</th>
                                                            <th scope="col">Giá gốc</th>
                                                            <th scope="col">Khuyến mãi (%)</th>
                                                            <th scope="col">Giá khuyến mại</th>
                                                            <th scope="col">Số lượng</th>
                                                            <th scope="col">Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $cnt = 0;
                                                            $product = [];
                                                        @endphp

                                                        @foreach ($listDetailCart as $detailCart)
                                                            @php
                                                                $cnt++;
                                                                $product = $detailCart->product;
                                                            @endphp
                                                            <tr class="row-in-list">
                                                                <th scope=" row">{{ $cnt }}</th>
                                                                <td>
                                                                    <a href="{{ route('product.edit', $detailCart->product->id) }}"
                                                                        class="thumbnail">
                                                                        @php
                                                                            $urlImage = getMainImage($detailCart->product->id);
                                                                        @endphp
                                                                        <img class="image-order" src="{{ url($urlImage) }}"
                                                                            alt="Ảnh của sản phẩm '{{ $product->name }}'"
                                                                            title="Ảnh của sản phẩm '{{ $product->name }}'"
                                                                            id="thumbnail_img">
                                                                    </a>
                                                                </td>
                                                                <td>{{ briefName($product->name, 5) }}</td>
                                                                <td>{{ currencyFormat($product->price) }}</td>
                                                                <td>{{ $product->discount ?? 'Không có' }}</td>
                                                                <td>{!! currencyFormat($detailCart->price_sale) !!}</td>
                                                                <td>{{ $detailCart->quantity }}</td>
                                                                <td>{!! getSubTotal($detailCart->price_sale, $detailCart->quantity) !!}</td>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="bg-info">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <th>TỔNG</th>
                                                            <th>TỔNG</th>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><b>{{ $cart->total_item }}</b></td>
                                                            <td><b>{{ currencyFormat($cart->total_price) }}</b></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            @else
                                                <p class="bg-white">Giỏ hàng đang rỗng!</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-4">
                                        <label for="status">Trạng thái giỏ hàng</label><BR>
                                        {!! showCartStatus($cart->status) !!}<BR>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <input type="submit" name="btn_update" class="btn btn-primary float-right" value="Cập nhật">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
