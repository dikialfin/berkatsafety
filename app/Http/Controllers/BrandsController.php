<?php

namespace App\Http\Controllers;

use App\Models\{Brands, SeoPage, Categories, Products, ProductBrand};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use Doctrine\DBAL\Schema\Index;

class BrandsController extends Controller
{
    public function index(Request $request, $slug)
    {

        $lang = app()->getLocale();
        $route = $request->route()->parameters();
        $search = $request->get('search');
        $perPage = $request->get('per_page', config('app.default_pagination'));

        $page = SeoPage::where('page', 'brands')->first();
        if (isset($page->seo_setting)) {
            $page->seo_setting = json_decode($page->seo_setting, true);
        }

        $brands = Brands::select(['name', 'slug', 'logo', 'id'])->orderBy('order_number', 'asc')->paginate($perPage);

        if ($search !== null || $search !== '') {
            $brands = Brands::select(['name', 'slug', 'logo', 'id'])->where('name', 'like', "%$search%")->orderBy('order_number', 'asc')->paginate($perPage);
        }

        $data = [
            'meta_title' => isset($page->seo_setting['meta_title_' . $lang]) ? $page->seo_setting['meta_title_' . $lang] : 'Welcome Berkat Safety',
            'meta_keyword' => isset($page->seo_setting['keyword_' . $lang]) ? join(', ', explode(',', $page->seo_setting['keyword_' . $lang])) : 'Welcome Berkat Safety',
            'meta_description' => isset($page->seo_setting['meta_description_' . $lang]) ? $page->seo_setting['meta_description_' . $lang] : 'Welcome Berkat Safety',
            'meta_image' => asset('images/logo-home.png'),
            'brands' => $brands,
            'lang' => $lang,
        ];
        return view('page.brands', $data);
    }

    public function productByBrand(Request $request)
    {

        $brandSlug = $request->route()->parameters()['slug'];
        $perPage = $request->get('per_page', config('app.default_pagination'));
        $lang = app()->getLocale();
        $search = $request->get('search', null);
        $filterByCategory = $request->get('filterByCategory', null);

        $selectQuery = [
            'products.code',
            'products.name',
            'products.description_id',
            'products.description_en',
            'products.slug',
            'product_media.value as photoProduct',
            'brands.logo as brandLogo',
            'brands.name as brandName',
            'brands.description_id as brandsDescription_id',
            'brands.description_en as brandsDescription_en',
        ];

        $product = ProductBrand::select($selectQuery)
            ->where('brands.slug','=',$brandSlug)
            ->where('products.deleted_at','=',null)
            ->join('product_category','product_category.product_id','=','product_brand.product_id')
            ->join('brands','brands.id','=','product_brand.brand_id')
            ->join('products','products.id','=','product_brand.product_id')
            ->join('product_media', 'products.id', '=', 'product_media.product_id');

        if ($search) {
            $product->Where(function ($query) use ($search) {
                    $query->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.code', 'like', "%{$search}%")
                        ->orWhere('products.slug', 'like', "%{$search}%");
                });
        }

        if ($filterByCategory) {
            $categoryParent = Categories::where('slug','=',$filterByCategory)->first();
            $categoryChild = Categories::where('parent_id','=',$categoryParent->id)->get();
            $product->whereIn('product_category.category_id',$categoryChild->pluck('id'));
        }

        $product = $product->paginate($perPage);

        $category = Categories::with('children')
            ->where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'slug', 'logo']);

        $data = [
            'meta_title' => isset($page->seo_setting['meta_title_' . $lang]) ? $page->seo_setting['meta_title_' . $lang] : 'Welcome Berkat Safety',
            'meta_keyword' => isset($page->seo_setting['keyword_' . $lang]) ? join(', ', explode(',', $page->seo_setting['keyword_' . $lang])) : 'Welcome Berkat Safety',
            'meta_description' => isset($page->seo_setting['meta_description_' . $lang]) ? $page->seo_setting['meta_description_' . $lang] : 'Welcome Berkat Safety',
            'meta_image' => asset('images/logo-home.png'),
            'products' => $product,
            'lang' => $lang,
            'category' => $category,
            'brandSlug' => $brandSlug,
            'search' => $search,
            'filterByCategory' => $filterByCategory
        ];

        return view('page.brand_products', $data);
    }
}
