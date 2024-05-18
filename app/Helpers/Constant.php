<?php

namespace App\Helpers;

class Constant
{
    const GENDER = [
        '0' => "Nữ",
        '1' => "Nam",
        '2' => "Giới tính khác"
    ];

    const ACTION_ADMIN = [
        "LICENSED" => "Đã cấp quyền",
        "PENDING" => "Chờ xét duyệt",
        "DELETE" => "Xoá tạm thời",
        "DELETE_PERMANENTLY" => "Xoá vĩnh viễn",
        "RESTORE" => "Khôi phục"
    ];

    const ACTION_USER = [
        "ACTIVE" => "Đã cấp quyền",
        "PENDING" => "Chờ xét duyệt",
        "BLOCKED" => "Khoá tạm thời",
    ];

    const ACTION_ORDER = [
        "ORDERED" => "Đã đặt hàng",
        "PROCESSING" => "Đang xử lý",
        "PAID" => "Đã thanh toán",
        "CANCELLED" => "Đã huỷ",
    ];

    const ACTION_CART = [
        "ACTIVE" => "Đang chọn hàng",
        "PAID" => "Đã thanh toán",
        "EXPIRED" => "Đã hết hạn",
        "CANCELLED" => "Đã huỷ",
    ];

    const ACTION_BILL = [
        "UNPAID" => "Chưa thanh toán",
        "PAID" => "Đã thanh toán",
    ];

    const ACTION = [
        "LICENSED" => "Đã đăng",
        "PENDING" => "Chờ duyệt",
        "DELETE" => "Xoá tạm thời",
        "DELETE_PERMANENTLY" => "Xoá vĩnh viễn",
        "RESTORE" => "Khôi phục"
    ];

    const STATUS_USER = [
        '0' => 'Có hiệu lực',
        '1' => 'Chờ xét duyệt',
        '2' => 'Đã khoá',
    ];

    const STATUS_ADMIN = [
        '0' => 'Đã cấp quyền',
        '1' => 'Chờ xét duyệt',
        '2' => 'Trong thùng rác',
    ];

    const STATUS_ORDER = [
        '0' => 'Đã đặt hàng',
        '1' => 'Đang xử lý',
        '2' => 'Đã thanh toán',
        '3' => 'Đã huỷ',
    ];

    const STATUS_BILL = [
        '0' => 'Chưa thanh toán',
        '1' => 'Đã thanh toán',
    ];

    const STATUS_CART = [
        '0' => 'Đang chọn hàng',
        '1' => 'Đã thanh toán',
        '2' => 'Đã hết hạn',
        '3' => 'Đã huỷ',
    ];


    const STATUS = [
        '0' => 'Hiển thị',
        '1' => 'Ẩn',
        '2' => 'Xoá tạm thời',
    ];

    const TYPE_CATEGORY = [
        '0' => 'Sản phẩm',
        '1' => 'Bài viết',
    ];

    const STATUS_FAVORITE_PRODUCT = [
        '0' => 0,
        '1' => 1,
    ];

    const STATUS_PRODUCT = [
        '0' => 0,
        '1' => 1,
        '2' => 2,
    ];

    const LEVEL_IMAGE = [
        '0' => 0,
        // Main
        '1' => 1, // Thumb
    ];

    const PAYMENT_METHOD = [
        '0' => 'Thanh toán bằng tiền mặt',        // Cash
        '1' => 'Thanh toán bằng chuyển khoản',    // QR
    ];

    const ROLE_ADMIN = [
        'manager' => "Manager",
        'sales_manager' => "Sales manager",
        "admin" => "Adminstrator",
        "super" => "Super admin"
    ];

    const EXPIRY_DAY_NUMBER = 20;

}
