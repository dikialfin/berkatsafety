<?php

namespace App\Http\Controllers;

use App\Models\{Brands, Categories, Products, Setting};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;

class ProductsController extends Controller
{
    public function index(){
        $category = Categories::with('children')->where('parent_id', 0)->orderBy('name', 'asc')->get(['id','name', 'slug', 'logo']);
        if (isset($page->seo_setting)) {
            $page->seo_setting = json_decode($page->seo_setting, true);
        }

        $lang = app()->getLocale();
        $data = [
            'category' => $category,
            'meta_title' => isset($page->seo_setting['meta_title_'.$lang]) ? $page->seo_setting['meta_title_'.$lang] : 'Welcome Berkat Safety',
            'meta_keyword' => isset($page->seo_setting['keyword_'.$lang]) ? join(', ', explode(',', $page->seo_setting['keyword_'.$lang])) : 'Welcome Berkat Safety',
            'meta_description' => isset($page->seo_setting['meta_description_'.$lang]) ? $page->seo_setting['meta_description_'.$lang] : 'Welcome Berkat Safety',
            'meta_image' => asset('images/logo-home.png'),
        ];
        return view('page.category', $data);
    }

    public function detail(Request $request, $slug)
    {
        $route = $request->route()->parameters();
        $slug = $route['slug'];
        $detail = Products::with(['categories', 'brands', 'productMedia'])->where('slug', $slug)->first();
        
        Products::where('id', $detail->id)->update([
            'is_trending' => ($detail->is_trending + 1)
        ]);
        
        $brandId = $detail->brands->pluck('id')->toArray();
        $product = Products::with(['brands' => function($query) use($brandId) {
            $query->whereIn('brands.id', $brandId);
        }])->where('id', '!=',$detail->id)->orderBy('id', 'desc')->get();
        $setting = Setting::where('id', 1)->first();
        $lang = app()->getLocale();
        $data = [
            'detail' => $detail,
            'meta_title' => $detail->{'meta_title_' . $lang},
            'meta_keyword' => $detail->{'meta_keyword_' . $lang},
            'meta_description' => $detail->{'meta_description_' . $lang},
            'meta_image' => $detail->logo,
            'lang' => $lang,
            'product' => $product,
            'setting' => $setting
        ];
        return view('page.product_detail', $data);   
    }
}
