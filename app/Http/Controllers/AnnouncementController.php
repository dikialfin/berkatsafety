<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Blogs;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function detail(Request $request, $language,$id)
    {

        $detail = Announcement::where('id',$id)->first();
        
        $latestNews = Blogs::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        foreach($latestNews as $res) {
            $res->date = 'Last updated ' . Carbon::parse(strtotime($res->created_at))->diffForHumans();
        }

        $data = [
            'detail' => $detail,
            'meta_title' => '',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'lang' => $language,
            'latestNews' => $latestNews
        ];
        return view('page.announcement_detail', $data);   
    }
}
