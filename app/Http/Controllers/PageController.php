<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Work;
use App\Page;
use App\Mail\Message;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function show(Request $request, $alias = 'main'){

        if($request->isMethod('post')){
            Mail::to(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->send(new Message($request->all()));
        }

        $data = [];

        $pages = new Page;

        $data['allPages']       = $pages->getAllPages();
        $data['currentPage']    = $pages->getCurrentPage($alias);

        if(in_array('works' || 'works-mini', $data['currentPage']['modules'])){
            $works = new Work;

            $data['allWorks'] = $works->getAllWorks();
        }

        return view('front.template', $data);
    }
}
