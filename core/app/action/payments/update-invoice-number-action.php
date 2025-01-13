<?php
if(count($_POST) > 0){
    $payment = SellData::getPaymentById($_POST["paymentId"]);
    $payment->invoice_number = strtoupper(trim($_POST["invoiceNumber"]));
    $updatedPayment = $payment->updatePaymentInvoiceNumber();

    if($updatedPayment){
        return http_response_code(200);
    }else{
        return http_response_code(500);
    }
}
else{
    return http_response_code(500);
}
