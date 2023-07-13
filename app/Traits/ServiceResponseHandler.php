<?php

namespace App\Traits;

trait ServiceResponseHandler
{
    public function successResponse($message, $data): object
    {
        return (object)[
            'status'    => true,
            'message'   => $message,
            'data'      => $data
        ];
    }

    public function errorResponse($message, $data): object
    {
        return (object)[
            'status'    => false,
            'message'   => $message,
            'data'      => $data
        ];
    }

    public function unAuthorizedResponse(): object
    {
        return (object)[
            'status'    => false,
            'message'   => 'unauthorized',
            'data'      => null,
        ];
    }
}
