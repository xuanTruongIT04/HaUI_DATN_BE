@extends('layouts.admin')

@section('content')
    <div id="list" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <div id="title-btn-add">
                    <h5 class="m-0 ">Theo dõi sản phẩm bán ra</h5>
                </div>
                <div class="form-search form-inline">
                    <form method="POST" action="/export/order-list/">
                        @csrf
                        <input type="hidden" id="start-date" name="start_date" class="form-control" value="{{ request('start_date') ?? request('start_date') }}">
                        <input type="hidden" id="end-date" name="end_date" class="form-control" value="{{ request('end_date') ?? request('end_date')  }}">

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-file-excel mr-1"></i> Xuất danh sách đơn hàng
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="form-action form-inline py-3">
                        <div class="form-group mr-5">
                            <label for="start-date" class="mr-1">Từ ngày:</label>

                            <input type="date" id="start-date" name="start_date" class="form-control" value="{{ request('start_date') ?? request('start_date') }}">
                        </div>
                        <div class="form-group mr-4">
                            <label for="end-date" class="mr-1">Đến ngày:</label>
                            <input type="date" id="end-date" name="end_date" class="form-control" value="{{ request('end_date') ?? request('end_date')  }}">
                        </div>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">

                    </div>
                    @if (!empty(request()->start_date) || !empty(request()->end_date))
                        <div class="count-product"><span>Kết quả tìm kiếm theo ngày: <b>{{ $countProducts }}</b> sản
                                phẩm</span>
                        </div>
                    @endif
                    <div class="text-muted"><small>Không chọn thì mặc định sẽ hiển thị danh sách sản phẩm bán ra trong ngày</small></div>

                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkAll">
                                </th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Tên SP</th>
                                <th scope="col">Mã đơn hàng</th>
                                <th scope="col">Tên khách hàng</th>
                                <th scope="col">Thời gian đặt</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($orderInDays) > 0)
                                @php
                                    $cnt = empty(request()->page) ? 0 : (request()->page - 1) * 20;
                                @endphp
                                @foreach ($orderInDays as $orderInDay)
                                    @php
                                        $detailOrders = $orderInDay?->detailOrders;
                                        @endphp
                                    @if (count($detailOrders) > 0)
                                        @foreach ($detailOrders as $detailOrder)
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
                                                <td>
                                                    <a href="{{ route('product.edit', $detailOrder->product->id) }}"
                                                        title="{{ $detailOrder->product->name }}">
                                                        {{ briefName($detailOrder->product->name, 5) }}
                                                    </a>


                                                </td>
                                                <td><a class="text-primary"
                                                        href="{{ route('order.edit', $orderInDay->id) }}">{{ brief_code($orderInDay->code, 20) }}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('user.edit', $orderInDay->cart->user->id) }}"
                                                        class="text-primary">{{ $orderInDay->cart->user->first_name }}
                                                    </a>
                                                </td>
                                                <td>{{ $orderInDay->order_date }}</td>
                                                <td>{!! fieldStatusOrder($orderInDay->status) !!}</td>
                                                @if (request()->status != 'trashed')
                                                    <td>
                                                        <a href="{{ route('order.edit', $orderInDay->id) }}"
                                                            title="Sửa"
                                                            class="btn btn-success btn-sm rounded-0 text-white"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            name="Edit"><i class="fa fa-edit"></i></a>
                                                    </td>
                                                @else
                                                    <td>
                                                        <a href="{{ route('order.restore', $orderInDay->id) }}"
                                                            title="Sửa"
                                                            class="btn btn-success btn-sm rounded-0 text-white"
                                                            type="button" data-toggle="tooltip"
                                                            onclick="return confirm('Bạn có chắc chắn muốn khôi phục sản phẩm {{ $orderInDay->name }}?')"
                                                            data-placement="top" name="Restore"><i
                                                                class="fas fa-trash-restore-alt"></i></a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="bg-white">Không tìm thấy sản phẩm nào!</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tìm thấy sản phẩm nào!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection
