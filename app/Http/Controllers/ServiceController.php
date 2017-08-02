<?php

namespace App\Http\Controllers;


class ServiceController extends Controller
{


//    public function stat()
//    {
//        return [
//            'last_hour' => [
//                'sent' => Post::query()->Sent()->LastHours(1)->count(),
//                'failed' => Post::query()->Failed()->LastHours(1)->count(),
//                'canceled' => Post::query()->Canceled()->LastHours(1)->count(),
//            ],
//            'last_6_hour' => [
//                'sent' => Post::query()->Sent()->LastHours(6)->count(),
//                'failed' => Post::query()->Failed()->LastHours(6)->count(),
//                'canceled' => Post::query()->Canceled()->LastHours(6)->count(),
//            ],
//            'last_12_hour' => [
//                'sent' => Post::query()->Sent()->LastHours(12)->count(),
//                'failed' => Post::query()->Failed()->LastHours(12)->count(),
//                'canceled' => Post::query()->Canceled()->LastHours(12)->count(),
//            ],
//            'last_24_hour' => [
//                'sent' => Post::query()->Sent()->LastHours(24)->count(),
//                'failed' => Post::query()->Failed()->LastHours(24)->count(),
//                'canceled' => Post::query()->Canceled()->LastHours(24)->count(),
//            ],
//            'last_7_days' => [
//                'sent' => Post::query()->Sent()->LastDays(7)->count(),
//                'failed' => Post::query()->Failed()->LastDays(7)->count(),
//                'canceled' => Post::query()->Canceled()->LastDays(7)->count(),
//            ],
//            'last_30_days' => [
//                'sent' => Post::query()->Sent()->LastDays(30)->count(),
//                'failed' => Post::query()->Failed()->LastDays(30)->count(),
//                'canceled' => Post::query()->Canceled()->LastDays(30)->count(),
//            ],
//        ];
//    }

    public function config()
    {
        return [
            'config' => config()->all(),
            'env' => getenv()
        ];
    }



}
