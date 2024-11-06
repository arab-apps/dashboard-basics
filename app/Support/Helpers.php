<?php

use App\Models\Setting;
use Google\Client;
if (!function_exists('uploadsPath')) {
    /**
     * @param $postfix
     * @return mixed|string|null
     */
    function uploadsPath($postfix = null)
    {
        if ($postfix == null)
            return null;

        if (filter_var($postfix, FILTER_VALIDATE_URL)) {
            return $postfix;
        }
        return asset('storage/' . $postfix);
    }
}

if (!function_exists('convertArabicNumerals')) {
    function convertArabicNumerals($number)
    {
        $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $westernNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($arabicNumerals, $westernNumerals, $number);
    }
}

if (!function_exists('startOfWeek')) {
    function startOfWeek()
    {
        return now()->startOfWeek();
    }
}

if (!function_exists('endOfWeek')) {
    function endOfWeek()
    {
        return now()->endOfWeek();
    }
}

if (!function_exists('settings')) {
    function settings($key = null)
    {
        $settings = Setting::first();
        //        $settings = [
//            'app_active' => true,
//            'force_update_android_version' => false,
//            'force_update_ios_version' => false,
//            'android_version' => "1.0.0",
//            'ios_version' => "1.0.0",
//            'firebase_secret_token' => null,
//        ];

        return $key ? $settings[$key] : $settings;
    }
}

if (!function_exists('getAccessToken')) {
    function getAccessToken($serviceAccountPath)
    {
        $client = new Client();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials();
        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }
}

function sendNotifications($notifications)
{

    $SERVER_API_KEY = settings('firebase_api_access_key');
    $serviceAccountPath = Storage::path('firebase-auth.json');
    $projectId = 'trio-snd-plus';
    $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
    $accessToken = getAccessToken($serviceAccountPath);

    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ];


    $multiCurl = array();
    $mh = curl_multi_init();
    curl_multi_setopt($mh, CURLMOPT_PIPELINING, CURLPIPE_MULTIPLEX);

    foreach ($notifications as $i => $notification) {
        $multiCurl[$i] = curl_init();
        curl_setopt($multiCurl[$i], CURLOPT_URL, $url);
        curl_setopt($multiCurl[$i], CURLOPT_HTTPHEADER, $headers);
        curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($multiCurl[$i], CURLOPT_POST, true);
        curl_setopt($multiCurl[$i], CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($multiCurl[$i], CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($multiCurl[$i], CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($multiCurl[$i], CURLOPT_POSTFIELDS, json_encode($notification, JSON_UNESCAPED_UNICODE));
        curl_multi_add_handle($mh, $multiCurl[$i]);
    }

    $index = null;
    do {
        curl_multi_exec($mh, $index);
        curl_multi_select($mh);
    } while ($index > 0);

    foreach ($multiCurl as $k => $ch) {
        $result[$k] = curl_multi_getcontent($ch);
        curl_multi_remove_handle($mh, $ch);
    }
    curl_multi_close($mh);
    // dd($result);
    // print_r($result);
}


if (!function_exists('notifyViaFirebase')) {
    // testing new firebase function
    function notifyViaFirebase($data, $to = null, $isTopic = false, $type = 'notification')
    {
        //    $SERVER_API_KEY = settings('firebase_api_access_key');
        // $serviceAccountPath = Storage::path('firebase-auth.json');
        // $projectId = 'trio-snd-plus';
        // $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';

        // $accessToken = getAccessToken($serviceAccountPath);
        $to = collect($to)->filter();
        // Prepare notification data
        if ($type === 'notification') {
            $data = array_merge([
                'title' => $data['title'] ?? '',
                'body' => $data['body'] ?? '',
                'type' => $data['type'] ?? '',
                'subject_id' => (string) $data['subject_id'] ?? '',
                'service_order_id' => $data['service_order_id'] ?? '',
                'image' => $data['image'] ?? '',
            ]);
            // dd($data);
        }
        $tokenCurls = [];
        // Prepare FCM message payload
        if ($isTopic && !is_array($to)) {
            $fields = [
                'message' => [
                    'topic' => 'general',
                    'notification' => [
                        'title' => $data['title'],
                        'body' => $data['body'],
                    ],
                    'data' => [
                        'type' => $data['type'],
                        'subject_id' => $data['subject_id'],
                        'service_order_id' => $data['service_order_id'],
                        'vibrate' => '1',
                        'badge' => '1',
                        'sound' => 'notification.mp3',
                    ],
                    'android' => [
                        'notification' => [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        ]
                    ],
                ],
            ];
        } else {
            foreach ($to as $token) {
                $tokenCurls[] = [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $data['title'],
                            'body' => $data['body'],
                            'image' => $data['image'],
                        ],
                        'data' => [
                            'type' => $data['type'],
                            'subject_id' => $data['subject_id'],
                            'service_order_id' => $data['service_order_id'],
                            'vibrate' => '1',
                            'badge' => '1',
                            'sound' => 'notification.mp3',
                        ],
                        'android' => [
                            'notification' => [
                                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            ]
                        ],
                        'apns' => [
                            'payload' => [
                                'aps' => [
                                    'alert' => [
                                        'title' => $data['title'],
                                        'body' => $data['body'],
                                    ],
                                    'sound' => 'default',
                                    'badge' => 1,
                                    'category' => 'FLUTTER_NOTIFICATION_CLICK',
                                ],
                            ],
                            'headers' => [
                                'apns-priority' => '10',
                            ],
                        ],
                    ],
                ];
            }

        }
        sendNotifications($tokenCurls);
    }

    // old firebase function
    function oldNotifyViaFirebase($data, $to = null, $isTopic = false, $type = 'notification')
    {
        $SERVER_API_KEY = settings('firebase_api_access_key');
        // dd($SERVER_API_KEY);
//        $SERVER_API_KEY = "AAAAjQbp8vU:APA91bHV3awgb7Di7KBYqXf7ZY4PyV99nLd5ZgTRlAkPlZZ6p8FITEMsYafUtzNvRpZVhZ-R7jk7fQL6aCyEmCjGu3cRibIpStnW8v0Cc2FRs_HnWifKxQKa-MQnDiJWUtmMg7NT6-Ep";
//        $SERVER_API_KEY = "AAAAxrKBa8A:APA91bFVaS2Bjyh2-1Hg2VY4iYnTAhja5bPQyxVcu66v57thS65cPCM6Akvu1OjokvaBhHN1gJ5LOX998Lb2HejCV1xCCwdTYbd80CC2JS9l6YUKmwsVnBfSNmCAyvoDSIooSknVPwrY";
        if ($type == 'data') {
            return;
        }

        if ($type == 'notification') {
            $data = [
                'title' => $data['title'],
                'body' => $data['body'] ?: '',
                'type' => $data['type'],

                'subject_id' => $data['subject_id'],
                'vibrate' => 1,
                'badge' => 1,
                'sound' => 'notification.mp3',
                'image' => isset($data['image']) && $data['image'] ? $data['image'] : null,
            ];
        }

        if ($isTopic && !is_array($to)) {
            $fields = [
                'to' => '/topics/general',
                $type => $data,
            ];
        } else {
            $fields = [
                'registration_ids' => $to,
                $type => $data,
                'data' => [
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    'type' => $data['type'],
                    'subject_id' => $data['subject_id'],

                ],
            ];
        }

        $headers = array
        (
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}


if (!function_exists('adminCountriesAndCities')) {

    function adminCountriesAndCities($admin = null)
    {
        $admin = $admin ?: auth()->guard('admin')->user();
        $adminCitiesIds = $admin->cities()->pluck('city_id')->toArray();
        $adminCountries = \DB::table('cities')->whereIn('id', $adminCitiesIds)->distinct()->pluck('country_id')->toArray();
        return [
            'admin' => $admin,
            'countries' => $adminCountries,
            'cities' => $adminCitiesIds
        ];
    }
}


function adminHasRoleOwner($admin = null)
{
    $admin = $admin ?: auth()->guard(name: 'admin')->user();

    return $admin->hasRole('owner');
}

function isSuperAdmin($admin = null)
{

    $admin = $admin ?: auth()->guard(name: 'admin')->user();
    return (bool) $admin->is_super_admin;

    // $super_admin = DB::table('admins')->first()->id;
    // return $super_admin == $admin->id;
}
/**
 * Checks if two arrays of cities have any common elements.
 *
 * This function is used to determine if two users have any common cities.
 * It compares two arrays of city IDs and returns true if there are no common elements,
 * indicating that the users do not share any cities.
 *
 * @param array $firstUserCities An array of city IDs for the first user.
 * @param array $secondUserCities An array of city IDs for the second user.
 *
 * @return bool Returns true if there are no common cities between the two users, false otherwise.
 */
function checkMatchingCities($firstUserCities, $secondUserCities)
{
    $commonCities = array_intersect($firstUserCities, $secondUserCities);
    return !empty($commonCities);
}
