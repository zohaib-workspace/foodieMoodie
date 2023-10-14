<?php

use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\App;

if (! function_exists('translate')) {
    function translate($key, $replace = [])
    {
        $key = strpos($key, 'messages.') === 0?substr($key,9):$key;
        $local = Helpers::default_lang();
        App::setLocale($local);
        try {
            $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));

            if (!array_key_exists($key, $lang_array)) {
                $processed_key = str_replace('_', ' ', Helpers::remove_invalid_charcaters($key));
                $lang_array[$key] = $processed_key;
                $str = "<?php return " . var_export($lang_array, true) . ";";
                file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);
                $result = $processed_key;
            } else {
                $result = trans('messages.' . $key, $replace);
            }
        } catch (\Exception $exception) {
            info($exception);
            $result = trans('messages.' . $key, $replace);
        }

        return $result;
    }
}
if (! function_exists('_response')) {
    function _response($statusCode = 0,$message = '',$response = [],$code = 501){
        return response()->json([
                'status_code' => $statusCode,
                'message' => $message,
                'response' => $response
            ], $code);
    }
}

if (! function_exists('chk_dmn')) {
function chk_dmn(){
    if(env('APP_URL') == 'http://127.0.0.1'){
       echo '';
    }else{
         echo 'public/';
    }
 }
}

if (! function_exists('site_url')) {
    function site_url(){
        return 'https://foodie.junaidali.tk/';
    }
}
if (! function_exists('local_url')) {
    function local_url(){
        return 'http://127.0.0.1:8000/';
    }
}
