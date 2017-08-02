<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class PushController extends Controller
{

    public function sendToDevice(Request $request)
    {
        $this->validate($request, [
            'device_fcm_token' => 'required|string',
            'title' => 'string',
            'body' => 'string',
            'sound' => 'string',
            'badge' => 'int',
            'click_action' => 'string',
            'body_loc_key' => 'string',
            'body_loc_args' => 'array',
            'title_loc_key' => 'string',
            'title_loc_args' => 'array',
            'icon' => 'string',
            'tag' => 'string',
            'color' => 'string',
        ]);

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);
        $option = $optionBuilder->build();

        $notificationBuilder = new PayloadNotificationBuilder(
            $request->get('title', 'OpenBroker')
        );

        $notificationBuilder
            ->setBody($request->get('body', NULL))
            ->setSound($request->get('sound', NULL))
            ->setBadge($request->get('badge', NULL))
            ->setClickAction($request->get('click_action', NULL))
            ->setBodyLocationKey($request->get('body_loc_key', NULL))
            ->setBodyLocationArgs($request->get('body_loc_args', NULL))
            ->setTitleLocationKey($request->get('title_loc_key', NULL))
            ->setTitleLocationArgs($request->get('title_loc_args', NULL))
            ->setIcon($request->get('icon', NULL))
            ->setTag($request->get('tag', NULL))
            ->setColor($request->get('color', NULL));

        $notification = $notificationBuilder->build();

        $token = $request->get('device_fcm_token');

        $downstreamResponse = FCM::sendTo($token, $option, $notification, null);

        return
        [
            'success' => $downstreamResponse->numberSuccess(),
            'failed' => $downstreamResponse->numberFailure(),
            'outdated' => $downstreamResponse->numberModification()
        ];

    }

}