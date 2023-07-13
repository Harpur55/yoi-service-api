<?php
  
namespace App\Enums;
 
enum DOKUPaymentTypeEnum:string {
    case VA = 'va';
    case CC = 'cc';
    case O2O = 'o2o';
    case EMONEY = 'e-money';
}