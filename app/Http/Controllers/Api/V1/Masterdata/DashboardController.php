<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Models\{Contact, Categories, Brands, Products, Catalogue, Csr, Blogs, User};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;

class DashboardController extends Controller
{

    public function index()
    {

        $data = [
            'user' => User::count(),
            'contact' => Contact::count(),
            'categories' => Categories::count(),
            'brands' => Brands::count(),
            'products' => Products::count(),
            'catalogue' => Catalogue::count(),
            'csr' => Csr::count(),
            'blogs' => Blogs::count(),
        ];
        return  response()->json([
            'data' => $data
        ]);
    }

}
