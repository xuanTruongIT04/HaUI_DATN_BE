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
                    <h5 class="m-0 ">Danh sách giỏ hàng</h5>
                </div>
                <div class="form-search form-inline">
                    <form action="#" method="GET">
                        @csrf
                        <input type="text" class="form-control form-search" name="keyWord"
                            value="{{ request()->input('keyWord') }}" placeholder="Tìm kiếm">
                        <input type="submit" name="btn_search" value="Tìm kiếm" class="btn btn-primary">
                        <input type="hidden" name="status"
                            value="{{ empty(request()->input('status')) ? 'active' : request()->input('status') }}" />
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Đang chọn
                        hàng <span class="text-muted">({{ $countCartStatus[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'paid']) }}" class="text-primary">Đã thanh
                        toán <span class="text-muted">({{ $countCartStatus[1] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'expired']) }}" class="text-primary">Đã hết hạn
                        <span class="text-muted">({{ $countCartStatus[2] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}" class="text-primary">Đã huỷ <span
                            class="text-muted">({{ $countCartStatus[3] }})</span></a>
                </div>
                <form action="{{ url('cart/action') }}" method="GET">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="act" id="">
                            <option value="">Chọn hành động</option>
                            @foreach ($listAct as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    @if (!empty(request()->keyWord))
                        <div class="count-cart"><span>Kết quả tìm kiếm: <b>{{ $countCartsSearch }}</b> giỏ hàng</span>
                        </div>
                    @endif
                    @if (count($carts) > 0)
                        <table class="table table-striped table-checkall">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="checkAll">
                                    </th>
                                    <th scope="col">#</th>
                                    <th scope="col">Họ</th>
                                    <th scope="col">Tên khách hàng</th>
                                    <th scope="col">Số lượng SP</th>
                                    <th scope="col">Tổng giá</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $cnt = empty(request()->page) ? 0 : (request()->page - 1) * 20;
                                @endphp
                                @foreach ($carts as $cart)
                                    @php
                                        $cnt++;
                                    @endphp
                                    <tr class="row-in-list">
                                        <td>
                                            <input type="checkbox" name="listCheck[]" value="{{ $cart->id }}"">
                                        </td>
                                        <th scope="row">{{ $cnt }}</th>
                                        <td>{{ $cart->user->last_name }}</td>
                                        <td> <a href="{{ route('user.edit', $cart->user->id) }}"
                                                class="text-primary">{{ $cart->user->first_name }}
                                            </a></td>
                                        <td>{{ $cart->total_item }}</td>
                                        <td>{!! currencyFormat($cart->total_price) !!}</td>
                                        <td>{!! fieldStatusCart($cart->status) !!}</td>
                                        <td>
                                            <a href="{{ route('cart.edit', $cart->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" name="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <tr>
                            <td colspan="7" class="bg-white">Không tồn tại giỏ hàng nào ở trạng thái này!</td>
                        </tr>
                    @endif
                </form>
                {{ $carts->links() }}
            </div>
        </div>
    </div>
@endsection
