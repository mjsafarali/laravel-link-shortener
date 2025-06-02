<?php

namespace App\Utility;

use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * ApiResponse constructor.
     * @param array $data
     * @param int $apiStatus
     * @param int $httpStatus
     * @param array $headers
     * @param int $options
     */
    public function __construct(
        $data = ["message" => "empty"],
        $apiStatus = 200,
        $httpStatus = 200,
        $headers = [],
        $options = 0
    ) {
        $response = [];
        $response['status'] = $apiStatus;
        $response['data'] = $data;
        parent::__construct($response, $httpStatus, $headers, $options);
    }
}
