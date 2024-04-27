@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin đơn hàng
            </div>
            <div class="card-body">
                <form action="{{ url("order/update/{$order->id}") }}" method='POST'>
                    @csrf
                    <div class="row mb-4">
                        <div class="col-6 pr-4">
                            <div class="form-group">
                                <label for="order-code">Mã đơn hàng</label>
                                <input class="form-control no-edit" type="text" name="code readonly" id="code"
                                    readonly="readonly" value="{{ $order->code }}">
                                @error('code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Họ khách hàng</label>
                                <input class="form-control no-edit" readonly="readonly" type="text" name="last_name"
                                    id="name" value="{{ $order->cart->user->last_name }}">
                                @error('last_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Tên khách hàng</label>
                                <input class="form-control no-edit" readonly="readonly" type="text" name="first_name"
                                    id="name" value="{{ $order->cart->user->first_name }}">
                                @error('first_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="number-phone">Số điện thoại</label>
                                <input class="form-control no-edit" type="text" name="phone" id="number-phone"
                                    value="{{ $order->cart->user->phone }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fax">Số FAX</label>
                                <input class="form-control no-edit" type="text" name="fax" id="fax"
                                    value="{{ $order->cart->user->fax }}">
                                @error('fax')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control no-edit" type="email" name="email" id="email"
                                    value="{{ $order->cart->user->email }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">

                            <div class="form-group">
                                <label for="address-delivery">Địa chỉ nhận hàng</label>
                                <input class="form-control no-edit" type="text" name="address_delivery"
                                    id="address-delivery" value="{{ $order->address_delivery }}">
                                @error('address_delivery')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="payment-method">Hình thức thanh toán</label> <BR>
                                {!! showPaymentMethod($order->cart->payment_method) !!} 
                                @error('payment_method')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">Trạng thái đơn hàng</label><BR>
                                {!! showOrderStatus($order->status) !!}<BR>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <input type="submit" name="btn_update" class="btn btn-primary" value="Cập nhật">
                </form>
            </div>
        </div>
    </div>
@endsection