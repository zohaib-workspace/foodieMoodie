<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\Banner;
use App\Models\about;
use App\Models\terms_and_condition;
use App\Models\privacy_policy;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // echo "index";
        // exit;
        // try {
        //     DB::connection()->getPdo();
        // } catch (\Exception $e) {
        //     die("Could not connect to the database.  Please check your configuration. error:" . $e );
        // }

        // dd(DB::table('business_settings')->first());
        // exit();

        // return view('home');
        return redirect('/admin');
    }

    public function terms_and_conditions()
    {
        $data = self::get_settings('terms_and_conditions');
        return view('terms-and-conditions', compact('data'));
    }

    public function about_us()
    {
        $data = self::get_settings('about_us');
        return view('about-us', compact('data'));
    }

    public function contact_us()
    {
        return view('contact-us');
    }

    public function privacy_policy()
    {
        $data = self::get_settings('privacy_policy');
        return view('privacy-policy', compact('data'));
    }


    public function show_about_responsive()
    {
        $about = About::where("status", '=', '1')->first();
        return view('about_us_res', compact('about'));
    }

    public function show_terms_responsive()
    {
        $terms = terms_and_condition::where("status", '=', '1')->first();
        return view('terms_and_con_res', compact('terms'));
    }

    public function show_privacy_responsive()
    {
        $privacy = privacy_policy::where("status", '=', '1')->first();
        return view('privacy_policy_res', compact('privacy'));
    }


    public static function get_settings($name)
    {
        $config = null;
        $data = BusinessSetting::where(['key' => $name])->first();
        if (isset($data)) {
            $config = json_decode($data['value'], true);
            if (is_null($config)) {
                $config = $data['value'];
            }
        }
        return $config;
    }
    //user Functions
    public function user(Request $request)
    {
        // return Auth()->user();
        $type = $request->query('business_type', '');

        $categories = Category::where(['position' => 0, 'status' => 1])->when(!empty($type), function ($q) use ($type) {
            if ($type > 0)
                $q->where('business_type', $type);
        })->orderBy('priority', 'desc')->get();

        $data['categories'] = Helpers::category_data_formatting($categories, true);
        $zone_id = session()->get('zone_id');
        if ($zone_id) {
            $data['restaurants'] = Restaurant::whereIn('zone_id', $zone_id)->get();
        } else {

            $data['restaurants'] = Restaurant::all();
        }
        return view('home.index', $data);
    }

    public function contact()
    {
        return view('home.contacts');
    }

    public function help()
    {
        return view('home.help');
    }
}
