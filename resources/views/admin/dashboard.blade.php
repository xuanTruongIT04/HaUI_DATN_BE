@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div id="wrapper-blog-order" class="row">
            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-white bg-success mb-3">
                    <div class="card-header card-header-dasboard max-width-13r ">
                        <a href="order/list?status=paid">ĐÃ THANH TOÁN</a>
                    </div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">
                            {{ getOrderPaid() }}</h5>
                        <p class="card-text card-text-dasboard">Đơn hàng thanh toán thành công</p>
                    </div>
                </div>
            </div>
            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-white bg-primary mb-3">
                    <div class="card-header card-header-dasboard max-width-10r">
                        <a href="order/list?status=processing">ĐANG XỬ LÝ</a>
                    </div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ getOrderProcessing() }}
                        </h5>
                        <p class="card-text card-text-dasboard">Đơn hàng trong quá trình xử lý (Shipping)
                        </p>
                    </div>
                </div>
            </div>
            <div class="col pl-8">
                <div class="card card-dasboard text-white bg-info mb-3">
                    <div class="card-header card-header-dasboard">
                        <a href="order/list?status=ordered">ĐÃ ĐẶT HÀNG</a>
                    </div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ getOrderOrdered() }}
                        </h5>
                        <p class="card-text card-text-dasboard">Đơn hàng đã tạo (chưa thêm TTGH)</p>
                    </div>
                </div>
            </div>
            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-dark bg-light mb-3">
                    <div class="card-header card-header-dasboard">SẢN PHẨM BÁN RA</div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center individual-number">{{ getTotalProductSold() }}
                        </h5>
                        <p class="card-text card-text-dasboard">Số lượng sản phẩm đã bán</p>
                    </div>
                </div>
            </div>

            <div class="col pl-8 pr-8">
                <div class="card card-dasboard text-white bg-warning mb-3">
                    <div class="card-header card-header-dasboard">
                        <a href="order/list?status=paid">DOANH SỐ</a>
                    </div>
                    <div class="card-body card-body-dasboard">
                        <h5 class="card-title card-title-dasboard text-center">{{ getTotalSales() }}</h5>
                        <p class="card-text card-text-dasboard">(VNĐ) - Doanh số hệ thống </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header font-weight-bold">
                ĐƠN HÀNG MỚI (5 ĐƠN MỚI NHẤT)
            </div>
            @php
                $listOrderLatest = getNewOrder();
            @endphp
            <div class="card-body">
                @if (count($listOrderLatest) > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Mã đơn hàng</th>
                                <th scope="col">Thông tin khách hàng</th>
                                <th scope="col">Số lượng sản phẩm</th>
                                <th scope="col">Giá trị đơn</th>
                                <th scope="col">Chi tiết</th>
                                <th scope="col">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listOrderLatest as $order)
                                <tr>
                                    <th scope=" row">1</th>
                                    <td><a class="text-primary"
                                            href="{{ route('order.detail', $order->id) }}">{{ brief_code($order->code, 10) }}</a>
                                    </td>
                                    <td>{{ $order?->bill?->user->first_name }}<BR>
                                        {{ $order?->bill?->user->phone }}</td>
                                    <td>{{ getTotalOrder($order->id) }}</td>
                                    <td>{{ currencyFormat($order->total_mount) }}</td>
                                    <td>
                                        <a href="{{ route('order.detail', $order->id) }}"
                                            class="btn btn-info btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Edit">Chi tiết</a>
                                    </td>
                                    <td><span>{!! fieldStatusOrder($order->status) !!}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        @else
            <tr>
                <td colspan="7" class="bg-white">Không tìm thấy đơn hàng nào!</td>
            </tr>
            @endif
        </div>
        <!-- /.row -->
    </div>
    <!-- Main row -->
@endsection
