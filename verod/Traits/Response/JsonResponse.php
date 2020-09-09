<?php

namespace Midun\Traits\Response;

trait JsonResponse
{
    /**
     * Return generic json response with the given data.
     *
     * @param $data
     * @param int $statusCode
     * @param array $headers
     * @return \Midun\Http\JsonResponse
     */
    protected function respond($data, $statusCode = 200, $headers = [])
    {
        return response()->json($data, $statusCode, $headers);
    }

    /**
     * Respond with created.
     *
     * @param $data
     * @return \Midun\Http\JsonResponse
     */
    protected function respondCreated($data)
    {
        return $this->respond($data, 201);
    }

    /**
     * Respond with success.
     *
     * @param $data
     * @param int $statusCode
     * @return \Midun\Http\JsonResponse
     */
    protected function respondSuccess($data, $statusCode = 200)
    {
        return $this->respond([
            'success' => true,
            'data' => $data,

        ], $statusCode);
    }

    /**
     * Respond with error.
     *
     * @param $message
     * @param $statusCode
     * @return \Midun\Http\JsonResponse
     */
    protected function respondError($message = 'Bad request', $statusCode = 400)
    {
        return $this->respond([
            'success' => false,
            'errors' => [
                'message' => $message,
            ],
        ], $statusCode);
    }

    /**
     * Respond with no content.
     *
     * @return \Midun\Http\JsonResponse
     */
    protected function respondNoContent()
    {
        return $this->respondSuccess(null, 204);
    }

    /**
     * Respond with unauthorized.
     *
     * @param string $message
     * @return \Midun\Http\JsonResponse
     */
	protected function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->respondError($message, 401);
    }

    /**
     * Respond with forbidden.
     *
     * @param string $message
     * @return \Midun\Http\JsonResponse
     */
    protected function respondForbidden($message = 'Forbidden')
    {
        return $this->respondError($message, 403);
    }

    /**
	 * Respond with not found.
     *
     * @param string $message
     * @return \Midun\Http\JsonResponse
     */
	protected function respondNotFound($message = 'Not Found')
    {
        return $this->respondError($message, 404);
    }

    /**
     * Respond with internal error.
     *
     * @param string $message
     * @return \Midun\Http\JsonResponse
     */
    protected function respondInternalError($message = 'Internal Error')
    {
        return $this->respondError($message, 500);
    }
}
