<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| RandomNumber => Generate random number by given count
|--------------------------------------------------------------------------
|
| Just need one parameter ( $count ) as count
|
*/
if (!function_exists('helperRandomNumber')) {
    /**
     * @throws Exception Random int
     */
    function helperRandomNumber($count = 4): int
    {

        $max = 10 ** $count;
        $min = $max / 10 - 1;

        return random_int($min, $max);
    }
}

if (!function_exists('helperGetSmsTemplate')) {
    function helperGetSmsTemplate($key = '')
    {
        if (!$key) {
            return false;
        }
        $sms = DB::table('sms_templates')->select('template', 'params')->where('key', $key)->first();

        return $sms;
    }
}

if (!function_exists('helperEnglishNumber')) {
    function helperEnglishNumber($string = null)
    {
        if (!$string) {
            return false;
        }
        $persianDigits1 = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $persianDigits2 = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $pd1 = str_replace($persianDigits1, range(0, 9), $string);

        return str_replace($persianDigits2, array_reverse(range(0, 9)), $pd1);
    }
}
/*
|--------------------------------------------------------------------------
| ArrayAppend => Append arrays to your previous one.
|--------------------------------------------------------------------------
|
| 1-Works with array merge.
| 2-Both $old_one and $additional must be arrays to skip validator.
|
*/
if (!function_exists('helperArrayAppend')) {

    /**
     * @param null $old_one
     * @param null $additional
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    function helperArrayAppend($old_one = null, $additional = null)
    {
        $data = [
            'old_one' => $old_one,
            'additional' => $additional,
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'old_one' => 'array',
            'additional' => 'array',
        ]);
        if ($validator->fails()) {
            return (new App\Http\Controllers\Api\ResponseController)->generateResponse($validator->errors(), false, __('register.check_field'), 400);
        }


        return array_merge($old_one, $additional);
    }
}
/*
|--------------------------------------------------------------------------
| ArrayAppend => Append arrays to your previous one.
|--------------------------------------------------------------------------
|
| 1-Works with array merge.
| 2-Both $old_one and $additional must be arrays to skip validator.
|
*/
if (!function_exists('helperDiffTime')) {
    function helperDiffTime($target_time, $difference, $correct_time = null): array
    {
        if ($correct_time === null) {
            $correct_time = \Carbon\Carbon::now();
        } else {
            $correct_time = \Carbon\Carbon::make($correct_time);
        }
        $ex_time = Carbon::make($target_time)->addSeconds($difference);

        return [
            'is_past'=>$ex_time->isPast(),
            'duration'=>Carbon::now()->diff($ex_time)->format('%i:%s')
        ];
    }
}
if (!function_exists('helperFileSize')) {
    /**
     * Format bytes to kb, mb, gb, tb
     *
     * @param integer $size
     * @param integer $precision
     *
     * @return integer
     */
    function helperFileSize($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int)$size;
            $base = log($size) / log(1024);
            $suffixes = [' bytes', ' KB', ' MB', ' GB', ' TB'];

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}
/*
|--------------------------------------------------------------------------
| FindFilledData => Check your all arrays and return the filled ones.
|--------------------------------------------------------------------------
|
|
*/
if (!function_exists('helperFindFilledData')) {

    /**
     * @param array $data
     * @param bool $single_data
     * @return array
     */
    function helperFindFilledData(array $data, bool $single_data = true): array
    {

        $filled_data = [];
        foreach ($data as $key => $item) {
            if (!empty($item)) {
                $filled_data[$key] = $item;
                if ($single_data) {
                    break;
                }
            }
        }

        return $filled_data;
    }
}
