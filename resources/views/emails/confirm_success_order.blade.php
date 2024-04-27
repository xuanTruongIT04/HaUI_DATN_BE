<div
    style="margin:0;padding:0;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size: 15px;color:#444;line-height:18px">
    <div style="width:1024px;height:auto;padding:15px;margin:0px auto;background-color:#f2f2f2">
        <h1 style="font-size:19px;font-weight:550;color:#444;padding:0 0 5px 0;margin:0">
            Xin chào {{ $infoUser['name'] }}. Đơn hàng của bạn đã đặt thành công!
        </h1>
        <p
            style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#444;line-height:18px;font-weight:normal">
            Chúng tôi đang chuẩn bị hàng để bàn giao cho đơn vị vận chuyển</p>
        <div style="font-size:15px;margin:12px 0 0 0;border-bottom:1px solid #ddd">
            <span style="color:#444">Mã đơn hàng: </span><span
                style="font-weight:550; color: #000;">{{ $infoOrder['code'] }}</span><br>
            <span style="color:#444">Ngày đặt: </span> <span
                style="font-weight:550; color: #000;">{{ $orderDate }}</span><br>
        </div>

        <table
            style="margin:20px 0px;width:100%;border-collapse:collapse;border-spacing:2px;background:#f5f5f5;display:table;box-sizing:border-box;border:0;border-color:grey">
            <thead style="background:rgba(3, 185, 3, 0.712)">
                <tr>
                    <th
                        style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:20px;">
                        <strong>Tên khách hàng</strong>
                    </th>
                    <th
                        style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:20px;">
                        <strong>Email</strong>
                    </th>
                    <th
                        style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:20px;">
                        <strong>SĐT</strong>
                    </th>
                    <th
                        style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:20px;">
                        <strong>Địa chỉ giao hàng</strong>
                    </th>

                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom:1px solid #e1dcdc;font-size:1;margin-top:10px;line-height:30px">
                    <td style="padding:3px 9px">{{ $infoUser['name'] }}</td>
                    <td style="padding:3px 9px"><a href="mailto:{{ $infoUser['email'] }}"
                            target="_blank">{{ $infoUser['email'] }}</a></td>
                    <td style="padding:3px 9px">{{ $infoUser['phone'] }}</td>
                    <td style="padding:3px 9px">{{ $infoOrder['address_delivery'] }}</td>
                </tr>
            </tbody>
        </table>

        @if (!empty($listProduct) && is_array($listProduct))
            <table
                style="margin:20px 0px;width:100%;border-collapse:collapse;border-spacing:2px;background:#f5f5f5;display:table;box-sizing:border-box;border:0;border-color:grey">
                <thead style="background:rgba(3, 185, 3, 0.712)">
                    <tr>
                        <td
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>Ảnh</strong>
                        </td>
                        <td
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>Tên sản phẩm</strong>
                        </td>
                        <td
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>Giá</strong>
                        </td>
                        <td
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>Số lượng</strong>
                        </td>
                        <td
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>Thành tiền</strong>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalPrice = 0;
                    @endphp
                    @foreach ($listProduct as $item)
                        @php
                            $subTotal = $item['product']['quantity'] * $item['product']['price_sale'];
                            $totalPrice += $subTotal;
                        @endphp
                        <tr style="border-bottom:1px solid #e1dcdc">
                            <td style="padding:4px"><img style="display:block;width:55px;height:70px;object-fit:cover"
                                    src="{{ $item['product']['mainImage'] }}" class="CToWUd a6T" data-bit="iit"
                                    tabindex="0"></td>
                            <td style="padding:3px 9px;vertical-align:middle">
                                {{ $item['product']['name'] }}
                            </td>
                            <td style="padding:3px 9px;vertical-align:middle">
                                {!! currencyFormat($item['product']['price_sale']) !!}
                            </td>
                            <td style="padding:3px 9px;vertical-align:middle">
                                {{ $item['product']['quantity'] }}
                            </td>
                            <td style="padding:3px 9px;vertical-align:middle">
                                {!! currencyFormat($subTotal) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if (!empty($infoCoupon))
            <table
                style="margin:20px 0px;width:100%;border-collapse:collapse;border-spacing:2px;background:#f5f5f5;display:table;box-sizing:border-box;border:0;border-color:grey">
                <thead style="background:rgba(3, 185, 3, 0.712)">
                    <tr>
                        <th
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>Tên coupon</strong>
                        </th>
                        <th
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>Mã coupon</strong>
                        </th>
                        <th
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>Ngày bắt đầu</strong>
                        </th>
                        <th
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>Ngày kết thúc</strong>
                        </th>
                        <th
                            style="text-align:left;background-color:rgba(3, 185, 3, 0.712);padding:6px 9px;color:#fff;text-transform:capitalize;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:14px">
                            <strong>GIẢM THÀNH TIỀN</strong>
                        </th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid yellowgreen;">
                    <tr style="border-bottom:1px solid #e1dcdc">
                        <td style="padding:4px">{{ $infoCoupon->name }}</td>
                        <td style="padding:3px 9px">{{ $infoCoupon->code }}</td>
                        <td style="padding:3px 9px">{{ $infoCoupon->start_date }}</td>
                        <td style="padding:3px 9px">{{ $infoCoupon->end_date }}</td>
                        <td style="padding:3px 9px">-
                            {{ getPricePromotion($totalPrice, $infoCoupon->percent) }}</td>
                    </tr>
                </tbody>
                <tfoot style="font-weight: 550">
                    <tr
                        style="background-color: #f4f46b;height: 31px; border-bottom: 1px solid yellowgreen;color: #000;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="padding-left:8px;">Tổng còn</td>
                        <td style="padding-left:17px; background: #ffff004f;">
                            {{ currencyFormat($infoOrder['total_amount']) }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif

        <div>
            <p>Quý khách vui lòng giữ lại hóa đơn, hộp sản phẩm và phiếu bảo hành (nếu có) để đổi trả hàng hoặc bảo hành
                khi cần thiết.</p>
            <p>Liên hệ Hotline <strong style="color:#099202">0374.993.702</strong> (8-21h cả T7,CN).</p>
            <div style="height:auto">
                <p>
                    Quý khách nhận được email này vì đã dùng email này đặt hàng tại cửa hàng trực tuyến Lotus Thé.
                    <br><br>
                    Nếu không phải quý khách đặt hàng vui lòng liên hệ số điện thoại 0374.993.702 hoặc email
                    <a style="color:red; font-weight:550;" href="mailto:{{ $infoUser['email'] }}"
                        target="_blank">{{ $infoUser['email'] }}</a> để hủy đơn hàng
                </p>
            </div>
            <p>
                <strong>SabujCha cảm ơn quý khách đã đặt hàng, chúng tôi sẽ không ngừng nổ lực để phục vụ quý khách tốt
                    hơn!</strong>
            </p>
        </div>
    </div>
</div>
