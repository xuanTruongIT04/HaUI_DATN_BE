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
                    <h5 class="m-0 ">Danh sách hoá đơn</h5>
                </div>
                <div class="form-search form-inline">
                    <form action="#" method="">
                        @csrf
                        <input type="text" class="form-control form-search" name="key_word"
                            value="{{ request()->input('key_word') }}" placeholder="Tìm kiếm theo tên KH">
                        <input type="submit" name="btn_search" value="Tìm kiếm" class="btn btn-primary">
                        <input type="hidden" name="status"
                            value="{{ empty(request()->input('status')) ? 'active' : request()->input('status') }}" />
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'unPaid']) }}" class="text-primary">Chưa thanh
                        toán<span class="text-muted">({{ $countBillStatus[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'paid']) }}" class="text-primary">Đã thanh
                        toán<span class="text-muted">({{ $countBillStatus[1] }})</span></a>
                </div>
                <form action="{{ url('bill/action') }}" method="GET">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="act" id="">
                            <option value="">Chọn</option>
                            @foreach ($listAct as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    @if (!empty(request()->key_word))
                        <div class="count-user"><span>Kết quả tìm kiếm: <b>{{ $countBillsSearch }}</b> hoá đơn</span>
                        </div>
                    @endif
                    @if ($countBills > 0)
                        <table class="table table-striped table-checkall">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="checkAll">
                                    </th>
                                    <th scope="col">#</th>
                                    <th scope="col">Mã đơn hàng</th>
                                    <th scope="col">Tên KH</th>
                                    <th scope="col">Tổng giá</th>
                                    <th scope="col">Thời gian đặt</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_price = 0;
                                    $cnt = empty(request()->page) ? 0 : (request()->page - 1) * 20;
                                @endphp
                                @if (count($bills) > 0)
                                    @foreach ($bills as $bill)
                                        @php
                                            $cnt++;
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="listCheck[]" value="{{ $bill->id }}">
                                            </td>
                                            <th scope=" row">{{ $cnt }}</th>
                                            <td><a class="text-primary"
                                                    href="{{ route('order.edit', $bill->order->id) }}">{{ brief_code($bill->order->code, 12) }}</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('user.edit', $bill->user->id) }}"
                                                    class="text-primary">{{ $bill->user->first_name }}
                                                </a>
                                            </td>
                                            <td>{!! currencyFormat($bill->order->total_mount) !!}</td>
                                            <td>{!! date('H:i:s-d/m/Y', strtotime($bill->created_at)) !!}</td>
                                            <td>{!! fieldStatusBill($bill->status) !!}</td>
                                            <td><a href="{{ route('bill.detail', $bill->id) }}"
                                                    class="btn btn-info btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Chi tiết">Chi tiết</a>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="bg-white">Không tồn tại hoá đơn nào ở trạng thái này!
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @else
                        <tr>
                            <td colspan="7" class="bg-white">Không tồn tại hoá đơn nào!</td>
                        </tr>
                    @endif
                </form>
                {{ $bills->links() }}
            </div>
        </div>
    </div>
@endsection
