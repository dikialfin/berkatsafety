<?php

namespace App\Http\Controllers;

use App\Models\{Brands, SeoPage, Categories, Products, ProductBrand};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;

class BrandsController extends Controller
{
    public function index(Request $request, $slug){
        $route = $request->route()->parameters();
        
        $page = SeoPage::where('page', 'brands')->first();
        if (isset($page->seo_setting)) {
            $page->seo_setting = json_decode($page->seo_setting, true);
        }
        $category = Categories::with('children')
            ->where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get(['id','name', 'slug', 'logo']);

        $brands = Brands::orderBy('order_number', 'asc')
            ->get(['name', 'slug', 'logo', 'id']);

        $detail = null;

        $search = $request->get('search'); 
        $perPage = $request->get('per_page', config('app.default_pagination'));
        // brand id
        $brandId = $brands ? $brands->pluck('id')->toArray() : [];
        if ($slug && isset($route['slug'])) {
            $detail = Brands::orderBy('name', 'asc')
                ->where('slug', $route['slug'])
                ->first(['name', 'slug', 'logo', 'id', 'description_id', 'description_en']);

            $slug = $route['slug'];
            $brandId = Brands::orderBy('name', 'asc')
                ->where('slug', $slug)
                ->get(['id'])->pluck('id')->toArray();
        }
        // get product id
        $prodId = ProductBrand::whereIn('brand_id', $brandId)
            ->get(['product_id'])
            ->pluck('product_id')
            ->toArray();

        $product = Products::with(['categories.parent', 'brands'])
            ->whereIn('id', $prodId)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('brands', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $selectedCategory = [];
        foreach($product as $val) {
            foreach($val->categories as $_val) {
                $selectedCategory[] = $_val->id;
            }
        }

        $selectedCategory = Categories::whereIn('id', $selectedCategory)
            ->get(['parent_id'])->pluck('parent_id')->toArray();

        $lang = app()->getLocale();
        $data = [
            'meta_title' => isset($page->seo_setting['meta_title_'.$lang]) ? $page->seo_setting['meta_title_'.$lang] : 'Welcome Berkat Safety',
            'meta_keyword' => isset($page->seo_setting['keyword_'.$lang]) ? join(', ', explode(',', $page->seo_setting['keyword_'.$lang])) : 'Welcome Berkat Safety',
            'meta_description' => isset($page->seo_setting['meta_description_'.$lang]) ? $page->seo_setting['meta_description_'.$lang] : 'Welcome Berkat Safety',
            'meta_image' => asset('images/logo-home.png'),
            'category' => $category,
            'brands' => $brands,
            'lang' => $lang,
            'brandsId' => $slug ? $brandId : [],
            'products' => $product,
            'allbrand' => $slug ? false : true,
            'selectedCategory' => $selectedCategory,
            'detail' => $detail
        ];
        return view('page.brands', $data);
    }

}
