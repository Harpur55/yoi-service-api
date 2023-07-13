<?php

namespace App\Repository\Order;

interface OrderRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
    public function calculate($request);
    public function init($request);
    public function directOrder($id);
    public function callbackOrderPayment();
    public function fetchBySeller();
    public function fetchBySellerForCancelled();
    public function cancelOrder($id);
    public function fetchBySellerForIsApproved();
    public function approveOrder($id);
    public function fetchBySellerForIsWaitingShipping();
    public function requestPickup($id);
}
