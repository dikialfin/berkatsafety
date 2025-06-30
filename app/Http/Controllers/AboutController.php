<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{SeoPage, Blogs, AboutUs, Csr, CsrMedia};
use Carbon\Carbon;

class AboutController extends Controller
{
    public function index(Request $request, $slug){
        $route = $request->route()->parameters();
        $lang = app()->getLocale();
        $page = 'about-us';
        if (isset($route['slug'])) {
            $page = $route['slug'];
        }
        $about = AboutUs::where('slug', $page)->first();

        $menu = AboutUs::orderBy('id', 'asc')->get();

        $csr = [];
        if ($page == 'csr') {
            $search = $request->get('search'); 
            $perPage = $request->get('per_page', config('app.default_pagination'));
            $csr = Csr::where('csr_id', $about->id)
                ->when($search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })->paginate($perPage);
        }
        // dd($page);
        $data = [
            'meta_title' => $about->{'meta_title_'.$lang},
            'meta_keyword' => $about->{'keyword_'.$lang},
            'meta_description' => $about->{'meta_description_'.$lang},
            'meta_image' => asset('images/logo-home.png'),
            'lang' => $lang,
            'page' => $page,
            'menu' => $menu,
            'about' => $about,
            'csr' => $csr
        ];
        return view('page.about', $data);
    } 

    public function detailCsr(Request $request, $slug)
    {
        $route = $request->route()->parameters();
        $slug = $route['slug'];

        $detail = Csr::select('csr.*', 'user.name as user')
            ->leftJoin('user', 'user.id', 'csr.admin_id')
            ->where('slug', $slug)->first();
        $lang = app()->getLocale();


        $medialist = CsrMedia::where('csr_id','=',$detail->id)->where('deleted_at','=',null)->get();

        $csr = Csr::orderBy('id', 'desc')
            ->where('slug', '!=', $slug)
            ->limit(5)
            ->get();
            
        foreach($csr as $res) {
            $res->date = 'Last updated ' . Carbon::parse(strtotime($res->created_at))->diffForHumans();
        }
        $data = [
            'detail' => $detail,
            'meta_title' => $detail->{'meta_title_' . $lang},
            'meta_keyword' => $detail->{'meta_keyword_' . $lang},
            'meta_description' => $detail->{'meta_description_' . $lang},
            'meta_image' => $detail->logo,
            'lang' => $lang,
            'medialist' => $medialist,
            'news' => $csr
        ];
        return view('page.csr_details', $data);   
    }

}
