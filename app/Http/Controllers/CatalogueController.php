<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Brands, SeoPage, Products, Catalogue, BrandsCatalogue};
class CatalogueController extends Controller
{
    public function index(Request $request, $slug = null){
        $route = $request->route()->parameters();
        $page = SeoPage::where('page', 'catalogue')->first();
        if (isset($page->seo_setting)) {
            $page->seo_setting = json_decode($page->seo_setting, true);
        }
        $brands = Brands::orderBy('name', 'asc')->get(['name', 'slug', 'logo', 'id']);

        $search = $request->get('search'); 
        $perPage = $request->get('per_page', config('app.default_pagination'));

        $cataId = [];
        if (isset($route['slug'])) {
            $id = Brands::where('slug', $route['slug'])->first('id')->id;
            $cataId = BrandsCatalogue::where('brand_id', $id)->get(['catalog_id'])->pluck('catalog_id')->toArray();
        }
        $catalogue = Catalogue::when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($cataId, function ($query, $cataId) {
                $query->whereIn('id', $cataId);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        
        $lang = app()->getLocale();
        $data = [
            'meta_title' => isset($page->seo_setting['meta_title_'.$lang]) ? $page->seo_setting['meta_title_'.$lang] : 'Welcome Berkat Safety',
            'meta_keyword' => isset($page->seo_setting['keyword_'.$lang]) ? join(', ', explode(',', $page->seo_setting['keyword_'.$lang])) : 'Welcome Berkat Safety',
            'meta_description' => isset($page->seo_setting['meta_description_'.$lang]) ? $page->seo_setting['meta_description_'.$lang] : 'Welcome Berkat Safety',
            'meta_image' => asset('images/logo-home.png'),
            'brands' => $brands,
            'catalogue' => $catalogue,
            'lang' => $lang,
            'allBrand' => isset($route['slug']) ? false : true,
            'slug' => isset($route['slug']) ? $route['slug'] : null
        ];
        return view('page.catalogue', $data);
    }

    public function detail(Request $request, $slug)
    {
        $route = $request->route()->parameters();
        $slug = $route['slug'];
        $detail = Catalogue::with('brands')->where('slug', $slug)->first();
        $lang = app()->getLocale();

        $brandId = $detail->brands->pluck('id')->toArray();

        $catalogue = Catalogue::with(['brands' => function($query) use($brandId){
            $query->whereIn('brands.id', $brandId);
        }])->where('id', '!=', $detail->id)->limit(8)->orderBy('id', 'desc')->get();
        
        $data = [
            'detail' => $detail,
            'meta_title' => $detail->{'meta_title_' . $lang},
            'meta_keyword' => $detail->{'meta_keyword_' . $lang},
            'meta_description' => $detail->{'meta_description_' . $lang},
            'meta_image' => $detail->logo,
            'lang' => $lang,
            'catalogue' => $catalogue
        ];
        return view('page.catalogue_details', $data);   
    }
}
