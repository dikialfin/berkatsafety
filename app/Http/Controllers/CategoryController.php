<?php

namespace App\Http\Controllers;

use App\Models\{Brands, Categories, SeoPage, Products, ProductCategory};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;

class CategoryController extends Controller
{
    public function index(){
        $category = Categories::with('children')
            ->where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get(['id','name', 'slug', 'logo']);
            
        $page = SeoPage::where('page', 'category')->first();
        if (isset($page->seo_setting)) {
            $page->seo_setting = json_decode($page->seo_setting, true);
        }

        $brandId = Products::select('product_brand.brand_id')
            ->join('product_brand', 'products.id', 'product_brand.product_id')
            ->orderBy('products.is_trending', 'desc')
            ->groupBy('product_brand.brand_id')
            ->get();
        $brandId = $brandId->pluck('brand_id')->toArray();
        $brandFirst = Brands::whereIn('id', $brandId)->orderBy('order_number', 'asc')->get(['slug', 'banner', 'name'])->toArray();
        $brandTwo = Brands::whereNotIn('id', $brandId)->orderBy('order_number', 'asc')->get(['slug', 'banner', 'name'])->toArray();
        $brands = array_merge($brandFirst, $brandTwo);

        $lang = app()->getLocale();
        $data = [
            'category' => $category,
            'meta_title' => isset($page->seo_setting['meta_title_'.$lang]) ? $page->seo_setting['meta_title_'.$lang] : 'Welcome Berkat Safety',
            'meta_keyword' => isset($page->seo_setting['keyword_'.$lang]) ? join(', ', explode(',', $page->seo_setting['keyword_'.$lang])) : 'Welcome Berkat Safety',
            'meta_description' => isset($page->seo_setting['meta_description_'.$lang]) ? $page->seo_setting['meta_description_'.$lang] : 'Welcome Berkat Safety',
            'meta_image' => asset('images/logo-home.png'),
            'brands' => $brands,
            'lang' => $lang
        ];
        return view('page.category', $data);
    }

    public function detail(Request $request, $slug)
    {
        $route = $request->route()->parameters();
        $slug = $route['slug'];
        $detail = Categories::with(['parent', 'products', 'children'])
            ->where('slug', $slug)
            ->first();

        $category = Categories::with('children')
            ->where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get(['id','name', 'slug', 'logo']);

        $lang = app()->getLocale();
        $brands = Brands::orderBy('name', 'asc')->get(['name', 'slug', 'logo', 'id']);
        
        $categoryId = count($detail->children) ? $detail->children->pluck('id')->toArray() : [$detail->id];
        
        $prodId = ProductCategory::whereIn('category_id', $categoryId)
            ->get(['product_id'])
            ->pluck('product_id')
            ->toArray();
        
        $search = $request->get('search'); 
        $perPage = $request->get('per_page', config('app.default_pagination'));

        $product = Products::with(['categories', 'brands'])
            ->whereIn('id', $prodId)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('categories', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('brands', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(9);

        $brandSelectes = [];
        foreach($product as $val) {
            $brandSelectes[] = $val->brands->pluck('id')->toArray();
        }
        $mergedIds = array_merge(...$brandSelectes);
        $brandsId = array_unique($mergedIds);

        $data = [
            'detail' => $detail,
            'category' => $category,
            'meta_title' => $detail->{'meta_title_' . $lang} ?? $detail->name,
            'meta_keyword' => $detail->{'meta_keyword_' . $lang},
            'meta_description' => $detail->{'meta_description_' . $lang},
            'meta_image' => $detail->logo,
            'lang' => $lang,
            'brands' => $brands,
            'products' => $product,
            'brandsId' => $brandsId
        ];
        return view('page.category_details', $data);   
    }
}
