<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{SeoPage, Blogs};
use Carbon\Carbon;

class MediaController extends Controller
{
    public function index(Request $request){
        $page = SeoPage::where('page', 'blogs')->first();
        if (isset($page->seo_setting)) {
            $page->seo_setting = json_decode($page->seo_setting, true);
        }

        $search = $request->get('search'); 
        $perPage = $request->get('per_page', config('app.default_pagination'));
        $blogs = Blogs::with(['categories'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('categories', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })->paginate($perPage);
        
        $lang = app()->getLocale();

        // blog
        $blog = Blogs::orderBy('id', 'desc')
            ->limit(5)
            ->get();
        foreach($blog as $res) {
            $res->date = 'Last updated ' . Carbon::parse(strtotime($res->created_at))->diffForHumans();
        }

        foreach($blog as $res) {
            $res->date = 'Last updated ' . Carbon::parse(strtotime($res->created_at))->diffForHumans();
        }

        $data = [
            'meta_title' => isset($page->seo_setting['meta_title_'.$lang]) ? $page->seo_setting['meta_title_'.$lang] : 'Welcome Berkat Safety',
            'meta_keyword' => isset($page->seo_setting['keyword_'.$lang]) ? join(', ', explode(',', $page->seo_setting['keyword_'.$lang])) : 'Welcome Berkat Safety',
            'meta_description' => isset($page->seo_setting['meta_description_'.$lang]) ? $page->seo_setting['meta_description_'.$lang] : 'Welcome Berkat Safety',
            'meta_image' => asset('images/logo-home.png'),
            'blogs' => $blogs,
            'news' => $blog,
            'lang' => $lang
        ];
        return view('page.blogs', $data);
    } 
    
    public function detail(Request $request, $slug)
    {
        $route = $request->route()->parameters();
        $slug = $route['slug'];
        $detail = Blogs::select('blogs.*', 'user.name as user')
            ->with('categories')
            ->leftJoin('user', 'user.id', 'blogs.admin_id')
            ->where('slug', $slug)->first();
        $lang = app()->getLocale();

        $blog = Blogs::orderBy('id', 'desc')
            ->where('slug', '!=', $slug)
            ->limit(5)
            ->get();
        foreach($blog as $res) {
            $res->date = 'Last updated ' . Carbon::parse(strtotime($res->created_at))->diffForHumans();
        }

        $data = [
            'detail' => $detail,
            'meta_title' => $detail->{'meta_title_' . $lang},
            'meta_keyword' => $detail->{'meta_keyword_' . $lang},
            'meta_description' => $detail->{'meta_description_' . $lang},
            'meta_image' => $detail->logo,
            'lang' => $lang,
            'news' => $blog
        ];
        return view('page.blog_details', $data);   
    }
}
