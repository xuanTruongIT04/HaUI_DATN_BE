<?php
use App\Helpers\Constant;

// Category
if (!function_exists('fieldCategory')) {
    function fieldCategory($type)
    {
        $key = array_keys(Constant::TYPE_CATEGORY);
        $data = Constant::TYPE_CATEGORY;

        if ($type == $key[0]) {
            return '<span class="badge badge-secondary">' . $data[0] . '</span>';
        }
        return '<span class="badge badge-info">' . $data[1] . '</span>';
    }
}

if (!function_exists('templateCategoryType')) {
    function templateCategoryType($type = "")
    {
        $str = "<select name='type' id='type' class='form-control'>";

        $data = Constant::TYPE_CATEGORY;
        if (!empty($type)) {
            foreach ($data as $item => $ele) {
                $sel = "";
                if ($type == $item) {
                    $sel = "selected='selected'";
                }

                $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
            }
        } else {
            foreach ($data as $item => $ele) {
                $str .= "<option value=" . $item . " >" . $ele . "</option>";
            }
        }

        $str .= "</select>";

        return $str;
    }
}

// Admin
if (!function_exists('fieldRoleAdmin')) {
    function fieldRoleAdmin($role)
    {
        $roleAdminKey = array_keys(Constant::ROLE_ADMIN);
        $roleAdminValue = Constant::ROLE_ADMIN;
        if ($role == $roleAdminKey[0]) {
            return '<span class="badge badge-info">' . $roleAdminValue['manager'] . '</span>';
        } else if ($role == $roleAdminKey[1]) {
            return '<span class="badge badge-primary">' . $roleAdminValue['sales_manager'] . '</span>';
        } else if ($role == $roleAdminKey[2]) {
            return '<span class="badge badge-success">' . $roleAdminValue['admin'] . '</span>';
        }
        return '<span class="badge badge-warning">' . $roleAdminValue['super'] . '</span>';
    }
}

if (!function_exists('templateRoleAdmin')) {
    function templateRoleAdmin($role = "")
    {
        $str = "<select name='role' id='role' class='form-control'>";
        $data = Constant::ROLE_ADMIN;
        unset($data['super']);
        if (!empty($role)) {
            foreach ($data as $item => $ele) {
                $sel = "";
                if ($role == $item) {
                    $sel = "selected='selected'";
                }

                $str .= "<option value=" . $item . " " . $sel . " >" . $ele . "</option>";
            }
        } else {
            foreach ($data as $item => $ele) {
                $str .= "<option value=" . $item . " >" . $ele . "</option>";
            }
        }

        $str .= "</select>";

        return $str;
    }
}