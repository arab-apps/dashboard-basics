<?php


namespace App\Traits;
use Illuminate\Support\Facades\Request;
use App\Models\LogActivity as LogActivityModel;


class LogActivity
{


    public static function addToLog($subject_en,$subject_ar,$user,$provider,$link=null,$country_id = 1)
    {
    	$log = [];
    	$log['subject_en'] = $subject_en;
    	$log['subject_ar'] = $subject_ar;

    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	$log['admin_id'] = null;
		$log['user_id'] = isset($user) ? $user->id : null;
		$log['provider_id'] = isset($provider) ? $provider->id : null;
		$log['link'] = isset($link) ? $link : null;
		$log['country_id'] = isset($country_id) ? $country_id : null;
    	LogActivityModel::create($log);
    }


    


}