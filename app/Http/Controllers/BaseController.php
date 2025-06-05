<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Swagger Response
 *
 * @OA\Schema(
 *     title="BaseResponse",
 *     description="Base repsonse",
 * @OA\Xml(name="BaseResponse")
 * )
 */
class BaseController extends Controller
{
    /**
     * Swagger Property
     *
     * @OA\Property(
     *     title="success",
     *     format="int64",
     *     example=true
     * )
     *
     * @var bool
     */
    protected $success;
     /**
      * Swagger Property
      *
      * @OA\Property(
      *     title="data",
      *     type="array",
      * @OA\Items     (
      *          type="array",
      * @OA\Items()
      *      )
      * )
      *
      * @var array
      */
    protected $data;
     /**
      * Swagger Property
      *
      * @OA\Property(
      *     title="message",
      * )
      *
      * @var string
      */
    protected $message;

    /**
     * Return success response.
     *
     * @param mixed $result
     * @param mixed $message
     * @param int   $code
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, int $code = 200)
    {
        $response = [
            'message' => $message,
            // 'success' => true,
        ];

        $response = array_merge($response, $result);
        return response()->json($response, $code);
    }


    /**
     * Return error response.
     *
     * @param mixed $error
     * @param mixed $errorMessages
     * @param int   $code
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], int $code = 500)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
