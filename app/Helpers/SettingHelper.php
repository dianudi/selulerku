<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting($key, $default = null)
    {
        $setting = Setting::find($key);

        return $setting ? $setting->value : $default;
    }
}
