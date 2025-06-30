<?php

namespace App\Http\Controllers;

use App\Models\{Brands, SeoPage, Blogs, Categories, Products, Csr, AboutUs, Announcement, Catalogue, ImageSliderMedia, ProductBrand};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $page = SeoPage::where('page', 'home')->first();
        if (isset($page->seo_setting)) {
            $page->seo_setting = json_decode($page->seo_setting, true);
        }
        // blog
        $blog = Blogs::orderBy('id', 'desc')
            ->limit(3)
            ->get();
        foreach($blog as $res) {
            $res->date = 'Last updated ' . Carbon::parse(strtotime($res->created_at))->diffForHumans();
        }

        $catalogue = Catalogue::orderBy('id', 'desc')->limit(10)->get();
            
        // brands
        $brandsWithProducts = Brands::with('products')
            ->whereHas('products')
            ->orderBy('order_number', 'asc');

        $brandsWithoutProducts = Brands::with('products')
            ->whereDoesntHave('products')
            ->orderBy('order_number', 'asc');

        $brands = $brandsWithProducts->union($brandsWithoutProducts)
            ->limit(9)
            ->orderBy('order_number', 'asc')
            ->get();
                
        // banner slide
        $brandsSlide = Brands::orderBy('order_number', 'asc')
            ->get(['name', 'slug', 'banner as logo']);

        //Category
        $category = Categories::with('children')->where('parent_id', 0)
            ->orderByRaw('(SELECT COUNT(*) FROM categories AS child WHERE child.parent_id = categories.id) DESC')
            ->orderBy('id', 'desc')
            ->limit(9)
            ->get(['id','name', 'slug', 'logo', 'description_id', 'description_en']);
        
        // products
        $products = Products::with(['productMedia', 'brands'])
            ->orderBy('is_trending', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'slug', 'description_id', 'description_en', 'code']);
        
        $productBrand = Products::with(['productMedia', 'brands'])
            ->orderBy('is_trending', 'desc')
            ->get(['id', 'name', 'slug', 'description_id', 'description_en', 'code']);
        
        // product by category
        $productCategory = [];
        if (isset($category[0]) && $category[0]->children) {
            $cateId = $category[0]->children->pluck('id')->toArray();
            $productCategory = $this->getProductByCategory($cateId);
        }

        if (request('category')) {
            $categoryFilter = Categories::with('children')->where('parent_id', 0)->where('slug', request('category'))->first();
            $cateId = $categoryFilter->children->pluck('id')->toArray();
            $productCategory = $this->getProductByCategory($cateId);
        }
        
        $csr = Csr::orderBy('id', 'desc')->limit(6)->get();
        $about =  AboutUs::where('slug', 'about-us')->first();
        $lang = app()->getLocale();

        $homeImageSlider = ImageSliderMedia::where('deleted_at',null)->limit(6)->orderBy('created_at','desc')->get();

        $announcement = Blogs::where('is_announcement','=',true)->where('deleted_at',null)->limit(4)->orderBy('created_at','desc')->first();

        $data = [
            'meta_title' => isset($page->seo_setting['meta_title_'.$lang]) ? $page->seo_setting['meta_title_'.$lang] : 'Welcome Berkat Safety',
            'meta_keyword' => isset($page->seo_setting['keyword_'.$lang]) ? join(', ', explode(',', $page->seo_setting['keyword_'.$lang])) : 'Welcome Berkat Safety',
            'meta_description' => isset($page->seo_setting['meta_description_'.$lang]) ? $page->seo_setting['meta_description_'.$lang] : 'Welcome Berkat Safety',
            'meta_image' => asset('images/logo-home.png'),
            'news' => $blog,
            'lang' => $lang,
            'category' => $category,
            'brands' => $brands,
            'productCategory' => $productCategory,
            'products' => $products,
            'brandsSlide' => $brandsSlide,
            'csr' => $csr,
            'about' => $about,
            'catalogue' => $catalogue,
            'productBrand' => $productBrand,
            'homeImageSlider' => $homeImageSlider,
            'announcement' => $announcement
        ];

        return view('page.home', $data);
    }

    private function getProductByCategory($cateId = [])
    {
        $product = Products::with(['productMedia', 'brands'])
                ->join('product_category', 'products.id', '=', 'product_category.product_id')
                ->whereIn('product_category.category_id', $cateId)
                ->groupBy('products.id')
                ->get(['products.id', 'products.name', 'products.slug', 'products.description_id', 'products.description_en', 'code']);

        $lang = app()->getLocale();
        foreach($product as $val) {
            $val->description_lang = descriptionProduct($val->{'description_'.$lang});
        }
        return $product;
    }

    private function products()
    {
        return Products::with(['productMedia', 'brands'])->orderBy('id', 'desc')->limit(10)->get(['id', 'name', 'slug']);
    }

    public function productFliter(Request $request)
    {
        try {
            if ($request->type == 'category') {
                $category = Categories::with('children')->where(['parent_id' => 0, 'slug' => $request->slug])->first('id');
                $cateId = $category->children->pluck('id')->toArray();

                return response()->json([
                    'status' => 200,
                    'data' => $this->getProductByCategory($cateId)
                ]);
            }
            if ($request->type == 'brands') {
                $slug = $request->slug;
                $productId = ProductBrand::join('brands', 'brands.id', 'product_brand.brand_id')->where('brands.slug', $slug)->get(['product_id'])->pluck('product_id')->toArray();
                $productId = array_unique($productId);
                $products = Products::with(['productMedia'])
                    ->whereIn('id', $productId)
                    ->orderBy('is_trending', 'desc')
                    ->limit(25)
                    ->get(['id', 'name', 'slug', 'code']);

                return response()->json([
                    'status' => 200,
                    'data' => $products
                ]);
            }
        } catch (\Throwable $err) {
            return response()->json([
                'status' => 500,
                'data' => [],
                'message' => $err->getMessage()
            ]);
        }
    }

}
