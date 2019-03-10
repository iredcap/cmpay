<?php
/**
 *  +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2018 http://www.iredcap.cn All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 *  +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 *  +----------------------------------------------------------------------
 */

namespace app\api\service\payment;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use app\common\library\exception\OrderException;
use app\api\service\ApiPayment;
use Log;

class Paypal extends ApiPayment
{

    /**
     *
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function pp_web($order){
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->config['client_id'] ,
                $this->config['client_secret']
            )
        );

        // Create new payer and method
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // Set redirect URLs
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->config['return_url'] . '?success=true')
        ->setCancelUrl($this->config['return_url'] . '?success=false');

        // Set payment amount
        $amount = new Amount();
        $amount->setCurrency($order['currency'])
            ->setTotal($order['amount']);

        // Set transaction object
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription($order['subject'])
            ->setInvoiceNumber($order['trade_no']);

        // Create the full payment object
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        // Create payment with valid API context
        try {
            $payment->create($apiContext);

            // Get PayPal redirect URL and redirect the customer
            $approvalUrl = $payment->getApprovalLink();


        } catch (PayPalConnectionException $ex) {
            Log::error('Create Paypal API Error:'. $ex->getCode().' : '.$ex->getMessage());
            throw new OrderException([
                'msg'   => 'Create Paypal API Error:'. $ex->getCode().' : '.$ex->getMessage(),
                'errCode'   => 200009
            ]);
        }
        // Redirect the customer to $approvalUrl
        return [
            'order_qr' => $approvalUrl
        ];
    }


    /**
     * Paypal异步通知  【测试】
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return array|string
     */
    public function notify(){

        return ;
    }

    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *

     */
    public function callback(){
       return ;
    }
}