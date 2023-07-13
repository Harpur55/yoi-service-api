<?php

namespace App\Repository\Voucher;

use App\Models\Voucher;
use App\Traits\ServiceResponseHandler;
use Carbon\Carbon;

class VoucherRepository implements VoucherRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        return $this->successResponse('Successfully fetch vouchers', Voucher::all());
    }

    public function getById($id): object
    {
        $voucher = Voucher::find($id);

        if (isset($voucher)) {
            return $this->successResponse('Successfully fetch voucher', $voucher);
        } else {
            return $this->errorResponse('Voucher not found', null);
        }
    }

    public function create($request): object
    {
        $validated_request = $request->validated();
        $validated_request['valid_date'] = Carbon::createFromFormat('d/m/Y', $validated_request['valid_date']);

        $voucher = Voucher::create($validated_request);

        return $this->successResponse('Successfully create voucher', $voucher);
    }
}
