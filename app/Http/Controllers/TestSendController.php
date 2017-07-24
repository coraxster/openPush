<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sly\NotificationPusher\Adapter\Apns;
use Sly\NotificationPusher\Collection\DeviceCollection;
use Sly\NotificationPusher\Model\Device;
use Sly\NotificationPusher\Model\Message;
use Sly\NotificationPusher\Model\Push;
use Sly\NotificationPusher\PushManager;

class TestSendController extends Controller
{


    public function __construct()
    {
        //
    }


    public function send(Request $request)
    {

        // First, instantiate the manager and declare an adapter.
        $pushManager    = new PushManager(PushManager::ENVIRONMENT_DEV);
        $apnsAdapter = new Apns(array(
            'certificate' => '/path/to/your/apns-certificate.pem',
//            'passPhrase' => 'example',
        ));

// Set the device(s) to push the notification to.
        $devices = new DeviceCollection(array(
            new Device($request->device_token)
        ));

// Then, create the push skel.
        $message = new Message('This is an example.');

// Finally, create and add the push to the manager, and push it!
        $push = new Push($apnsAdapter, $devices, $message);
        $pushManager->add($push);
        $pushManager->push();

    }

}
