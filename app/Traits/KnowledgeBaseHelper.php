<?php

namespace App\Traits;


use App\Models\KnowledgeBase;
use GuzzleHttp\Client;

trait KnowledgeBaseHelper
{
  public function sendToProcessor(KnowledgeBase $kb){
    ini_set('max_execution_time', 2000); 

    $kb->doc_status = 'PROCESSING';
    $kb->update();
    $heyjen_url = env('HEYJEN_API_URL');
    $client = new Client();
    // $response = $client->request('POST', 
    // "$heyjen_url/reasoning/convert2kb/pdf", [
    //     'multipart' => [
    //         [
    //             'name'     => 'docfile',
    //             'filename' => $image_org,
    //             'contents' => fopen( $image_path, 'r' ),
    //         ],
    //         [
    //             'name' => "textbook_name",
    //             'contents' => $request->name,
    //         ],
    //     ]
    // ]);
    $response = $client->request('POST', 
    "$heyjen_url/reasoning/convert2kb/pdf-url", [
        'json' => [
            'url' => $kb->source,
            "document_name" => $kb->name,
        ]
    ]);
    $response = json_decode($response->getBody(), true);
    // Log::info("[sendToProcessor] response : {$response}");

    if(!isset($response['complete'])){
        return $this->sendError('Something goes wrong!',[]);
    }

    $kb->kb_id = $response['data']['kb-id'];
    $kb->doc_status = 'DONE';
    $kb->update();

  }

}