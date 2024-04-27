@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Chi tiết đơn hàng</h3>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="?">Bill</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->

    <div class="container-fluid">

        <div class="card">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <!-- /.col (left) -->
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin đơn hàng</h3>
                        </div>
                        <div class="card-body" id="information-order">
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
                                    @if (!empty($listDetailOrder))
                                        @php
                                            $cnt = 0;
                                            $totalPrice = 0;
                                            $totalItem = 0;
                                        @endphp

                                        @foreach ($listDetailOrder as $detailOrder)
                                            @php
                                                $cnt++;
                                            @endphp
                                            <tr class="row-in-list">
                                                <th scope=" row">{{ $cnt }}</th>
                                                <td>
                                                    <a href="{{ route('product.edit', $detailOrder->product->id) }}"
                                                        class="thumbnail">
                                                        @php
                                                            $urlImage = getMainImage($detailOrder->product->id);
                                                        @endphp
                                                        <img class="image-order" src="{{ url($urlImage) }}"
                                                            alt="Ảnh của sản phẩm {{ $detailOrder->product->name }}"
                                                            title="Ảnh của sản phẩm {{ $detailOrder->product->name }}"
                                                            id="thumbnail_img">
                                                    </a>
                                                </td>
                                                <td>{{ briefName($detailOrder->product->name, 5) }}</td>
                                                <td>{{ currencyFormat($detailOrder->product->price) }}</td>
                                                <td>{{ $detailOrder->product->discount ?? 'Không có' }}</td>
                                                <td>{!! currencyFormat($detailOrder->price_sale) !!}</td>
                                                <td>{{ $detailOrder->quantity }}</td>
                                                <td>{!! getSubTotal($detailOrder->price_sale, $detailOrder->quantity) !!}</td>
                                                @php
                                                    $totalItem += $detailOrder->quantity;
                                                    $totalPrice += $detailOrder->price_sale * $detailOrder->quantity;
                                                @endphp
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="bg-white">Không tìm thấy sản phẩm nào!</td>
                                        </tr>
                                    @endif
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
                                        <td><b>{{ $totalItem }}</b></td>
                                        <td><b>{{ currencyFormat($totalPrice) }}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="info-customer">
                            <div class="card total-price-order">
                                <div class="card-header font-weight-bold">
                                    Thông tin khách hàng
                                </div>
                                @if ($user)
                                    <div class="card-body" id="information-order">
                                        <table class="table table-striped table-checkall">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Tên KH</th>
                                                    <th scope="col">Họ KH</th>
                                                    <th scope="col">Tên đăng nhập</th>
                                                    <th scope="col">Số điện thoại</th>
                                                    <th scope="col">Số FAX</th>
                                                    <th scope="col">Địa chỉ email</th>
                                                    <th scope="col">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="row-in-list">
                                                    <td><a
                                                            href="{{ route('user.edit', $user->id) }}">{{ $user->first_name }}</a>
                                                    </td>
                                                    <td>{{ $user->last_name }}</td>
                                                    <td>{{ $user->username }}</td>
                                                    <td>{!! $user->phone !!}</td>
                                                    <td>{!! $user->fax !!}</td>
                                                    <td>{!! $user->email !!}</td>
                                                    <td>
                                                        <a href="{{ route('user.edit', $user->id) }}"
                                                            class="btn btn-success btn-sm rounded-0 text-white"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            name="Edit">Cập nhật</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="card-body" id="information-order">
                                        <span class="h-4">Không có phiếu giảm giá nào được áp dụng</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="infor-order-detail">
                            <div class="card total-price-order">
                                <div class="card-header font-weight-bold">
                                    Phiếu giảm giá
                                </div>
                                @if ($coupon)
                                    <div class="card-body" id="information-order">
                                        <table class="table table-striped table-checkall">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Tên phiếu</th>
                                                    <th scope="col">Mã phiếu</th>
                                                    <th scope="col">Khuyến mãi (%)</th>
                                                    <th scope="col">Khuyến mãi (VNĐ)</th>
                                                    <th scope="col">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $detailOrder->product = [];
                                                @endphp

                                                <tr class="row-in-list">
                                                    <td><a
                                                            href="{{ route('coupon.edit', $coupon->id) }}">{{ $coupon->name }}</a>
                                                    </td>
                                                    <td>{{ $coupon->code }}</td>
                                                    @php
                                                        $pricePromotion = currencyFormat(($totalPrice / 100) * $coupon->percent);
                                                    @endphp
                                                    <td>{!! $coupon->percent !!}%</td>
                                                    <td>{{ $pricePromotion }}</td>
                                                    <td>{!! currencyFormat($order->total_mount) !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="card-body" id="information-order">
                                        <span class="h-4">Không có phiếu giảm giá nào được áp dụng</span>
                                    </div>
                                @endif
                            </div>

                            <div class="detail-infor-order">
                                <div class="card-header font-weight-bold">
                                    Thông tin đơn hàng
                                </div>
                                <div class="card-body" id="information-order">
                                    <form action="{{ url("bill/detail/update/{$bill->id}") }}" method='POST'>
                                        @csrf
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <h6 class="order-code">
                                                        <i class="fas fa-barcode"></i>Mã đơn hàng
                                                    </h6>
                                                    <span class="detail">{{ $order->code }}</span>
                                                </div>

                                                <div class="form-group">
                                                    <h6 class="address-delivery">
                                                        <i class="fas fa-map-marker-alt"></i>Địa chỉ nhận hàng / Số điện
                                                        thoại
                                                    </h6>
                                                    <span class="detail">{{ $order->address_delivery }} / </span><span
                                                        class="detail">{{ $order->cart->user->phone }}</span>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <h6 class="payment-method">
                                                        <i class="fab fa-opencart"></i>
                                                        Thông tin vận chuyển
                                                    </h6>
                                                    {!! showPaymentMethod($order->payment_method) !!}
                                                </div>

                                                <div class="form-group">
                                                    <h6 class="order-status">
                                                        <label for="status" class="fw-550">
                                                            <i class="fas fa-chart-area"></i>Trạng thái đơn hàng
                                                        </label>
                                                    </h6>
                                                    {!! showOrderStatusNoUpdate($order->status) !!}
                                                    @error('status')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <input type="submit" name="btn_update" class="btn btn-primary float-right mt-4"
                                            value="Cập nhật">
                                    </form>
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
