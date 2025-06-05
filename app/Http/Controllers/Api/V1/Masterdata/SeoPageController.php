<?php
    namespace App\Http\Controllers\Api\V1\Masterdata;

    use App\Models\SeoPage;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Illuminate\Database\QueryException;
    use DB;
    use Illuminate\Support\Facades\Cache;

    class SeoPageController extends Controller
    {

        public function data(Request $request)
        {
            $data = SeoPage::where('page', $request->group)->first();
            if ($data) {
                $seoSetting = json_decode($data->seo_setting, true);

                $seoSetting['keyword_id'] = explode(',', $seoSetting['keyword_id']);
                $seoSetting['keyword_en'] = explode(',', $seoSetting['keyword_en']);

                $data->seo_setting = $seoSetting;
            }
            return response()->json([
                'data' => $data,
            ]);
        }

        public function store(Request $request)
        {
            $insert = [
                "page" => $request->group,
                "seo_setting" => json_encode([
                    'keyword_id' => $request->data['keyword_id'] ? join(",", $request->data['keyword_id']) : "",
                    'keyword_en' => $request->data['keyword_en'] ? join(",", $request->data['keyword_en']) : "",
                    'meta_title_id' => $request->data['meta_title_id'],
                    'meta_title_en' => $request->data['meta_title_en'],
                    'meta_description_id' => $request->data['meta_title_en'],
                    'meta_description_en' => $request->data['meta_title_en'],
                ])
            ];
            
            $seoSetting = SeoPage::updateOrCreate(
                [
                    'page' => $request->group,
                ],
                $insert
            );
            Cache::flush();
            return response()->json([
                'status' => 200,
                'data' => $seoSetting,
                'message' => 'Data submited!'
            ]);
        }
    }