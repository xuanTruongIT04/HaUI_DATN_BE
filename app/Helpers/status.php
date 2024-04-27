<?php

use App\Helpers\Constant;

if (!function_exists('fieldStatusAdmin')) {
    function fieldStatusAdmin($status)
    {
        $key = array_keys(Constant::STATUS_ADMIN);
        $data = Constant::STATUS_ADMIN;

        if ($status == $key[0]) {
            return '<span class="badge badge-success">' . $data[0] . '</span>';
        } else {
            if ($status == $key[1]) {
                return '<span class="badge badge-primary">' . $data[1] . '</span>';
            }
        }

        return '<span class="badge badge-dark">' . $data[2] . '</span>';
    }
}

if (!function_exists('fieldStatusCategory')) {
    function fieldStatusCategory($status)
    {
        $key = array_keys(Constant::STATUS);
        $data = Constant::STATUS;
        if ($status == $key[0]) {
            return '<span class="badge badge-success">' . $data[0] . '</span>';
        } else {
            if ($status == $key[1]) {
                return '<span class="badge badge-primary">' . $data[1] . '</span>';
            }
        }

        return '<span class="badge badge-dark">' . $data[2] . '</span>';
    }
}

if (!function_exists('fieldStatusUser')) {
    function fieldStatusUser($status)
    {
        $key = array_keys(Constant::STATUS_USER);
        $data = Constant::STATUS_USER;
        if ($status == $key[0]) {
            return '<span class="badge badge-success">' . $data[0] . '</span>';
        } else if ($status == $key[1]) {
            return '<span class="badge badge-primary">' . $data[1] . '</span>';
        }

        return '<span class="badge badge-dark">' . $data[2] . '</span>';
    }
}

if (!function_exists('templateUpdateStatus')) {
    function templateUpdateStatus($status)
    {
        $str = "<select name='status' id='status' class='form-control'>";

        $data = Constant::STATUS;

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

if (!function_exists('templateUpdateStatusUser')) {
    function templateUpdateStatusUser($status)
    {
        $str = "<select name='status' id='status' class='form-control'>";

        $data = Constant::STATUS_USER;

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

if (!function_exists('templateUpdateStatusAdmin')) {
    function templateUpdateStatusAdmin($status)
    {
        $str = "<select name='status' id='status' class='form-control'>";

        $data = Constant::STATUS_ADMIN;

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

if (!function_exists('fieldStatusOrder')) {
    function fieldStatusOrder($status)
    {
        $statusOrderKey = array_keys(Constant::STATUS_ORDER);
        $statusOrderValue = Constant::STATUS_ORDER;
        if ($status == $statusOrderKey[0]) {
            return '<span class="badge badge-secondary">' . $statusOrderValue[0] . '</span>';
        } else if ($status == $statusOrderKey[1]) {
            return '<span class="badge badge-primary">' . $statusOrderValue[1] . '</span>';
        } else if ($status == $statusOrderKey[2]) {
            return '<span class="badge badge-success">' . $statusOrderValue[2] . '</span>';
        }
        return '<span class="badge badge-dark">' . $statusOrderValue[3] . '</span>';
    }
}

if (!function_exists('fieldStatusBill')) {
    function fieldStatusBill($status)
    {
        $statusBillKey = array_keys(Constant::STATUS_BILL);
        $statusBillValue = Constant::STATUS_BILL;
        if ($status == $statusBillKey[0]) {
            return '<span class="badge badge-primary">' . $statusBillValue[0] . '</span>';
        }
        return '<span class="badge badge-success">' . $statusBillValue[1] . '</span>';
    }
}

if (!function_exists('fieldStatusCart')) {
    function fieldStatusCart($status)
    {
        $statusCartKey = array_keys(Constant::STATUS_CART);
        $statusCartValue = Constant::STATUS_CART;
        if ($status == $statusCartKey[0]) {
            return '<span class="badge badge-primary">' . $statusCartValue[0] . '</span>';
        } else if ($status == $statusCartKey[1]) {
            return '<span class="badge badge-primary">' . $statusCartValue[1] . '</span>';
        } else if ($status == $statusCartKey[2]) {
            return '<span class="badge badge-dark">' . $statusCartValue[2] . '</span>';
        }
        return '<span class="badge badge-dark">' . $statusCartValue[3] . '</span>';
    }
}

if (!function_exists('showPaymentMethod')) {
    function showPaymentMethod($method)
    {
        $str = "<select class='form-control' name='payment_method' id='payment-method' disabled>";
        $data = Constant::PAYMENT_METHOD;

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($method === $item) {
                $sel = "selected='selected'";
            }
            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

// Order
if (!function_exists('showOrderStatus')) {
    function showOrderStatus($status)
    {
        $str = "<select class='form-control' name='status' id='status'>";
        $data = Constant::STATUS_ORDER;

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}
// Order
if (!function_exists('showOrderStatusNoUpdate')) {
    function showOrderStatusNoUpdate($status)
    {
        $str = "<select class='form-control' name='status' id='status' disabled>";
        $data = Constant::STATUS_ORDER;

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

// Bill
if (!function_exists('showBillStatus')) {
    function showBillStatus($status)
    {
        $str = "<select class='form-control' name='status' id='status' disabled>";
        $data = Constant::STATUS_BILL;

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}

// Cart
if (!function_exists('showCartStatus')) {
    function showCartStatus($status)
    {
        $str = "<select class='form-control' name='status' id='status'>";
        $data = Constant::STATUS_CART;

        foreach ($data as $item => $ele) {
            $sel = "";
            if ($status == $item) {
                $sel = "selected='selected'";
            }

            $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
        }

        $str .= "</select>";

        return $str;
    }
}