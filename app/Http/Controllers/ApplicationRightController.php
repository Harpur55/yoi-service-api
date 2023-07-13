<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRightRequest;
use App\Http\Requests\UpdateApplicationRightRequest;
use App\Repository\ApplicationRight\ApplicationRightRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class ApplicationRightController extends Controller
{
    protected $applicationRightRepository;

    public function __construct(ApplicationRightRepositoryInterface $applicationRightRepository)
    {
        $this->applicationRightRepository = $applicationRightRepository;
    }

    public function index()
    {
        try {
            $res = $this->applicationRightRepository->fetchAll();

            return $this->successResponse($res->message, $res->data);
        } catch(Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function store(StoreApplicationRightRequest $request)
    {
        try {
            DB::beginTransaction();

            $res = $this->applicationRightRepository->create($request);

            if ($res->status) {
                DB::commit();

                return $this->successResponse($res->message, $res->data);
            } else {
                DB::rollBack();

                return $this->errorResponse($res->message, $res->data);
            }
        } catch(Exception $e) {
            DB::rollBack();

            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $res = $this->applicationRightRepository->fetchById($id);

            if ($res->status) {
                return $this->successResponse($res->message, $res->data);
            } else {
                return $this->errorResponse($res->message, $res->data);
            }
        } catch(Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function update(UpdateApplicationRightRequest $request)
    {
        try {
            DB::beginTransaction();

            $res = $this->applicationRightRepository->update($request);

            if ($res->status) {
                DB::commit();

                return $this->successResponse($res->message, $res->data);
            } else {
                DB::rollBack();

                return $this->errorResponse($res->message, $res->data);
            }
        } catch(Exception $e) {
            DB::rollBack();

            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function sendNotif()
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $dataArr = array('click_action' => 'FLUTTER_NOTIFICATION_CLICK');
        $notification = array('title' => 'title', 'text' => 'asd', 'sound' => 'default', 'badge' => '1',);
        $arrayToSend = array('to' => "/topics/all", 'notification' => $notification, 'data' => $dataArr, 'priority'=>'high');
        $fields = json_encode ($arrayToSend);
        $headers = array (
            'Authorization: key=' . "AAAA5tCDE9Q:APA91bHRBIurOvT7nWLYNokwzlloHIOJpZdbuP35ywZHP5nMKtw2r_mLXczKrc75WUhAegEh3iz-KiIfZyoIQFhvzhu_Ftc1w3ae7YzGYMCMlzaTB7zG12az9Ad2YzkplrSt0CjiPczR",
            'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        $result = curl_exec ( $ch );
        //var_dump($result);
        curl_close ( $ch );

        return $result;
    }
}
