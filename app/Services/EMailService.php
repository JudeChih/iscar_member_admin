<?php

namespace App\Services;

use \Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

define('SALES_VERIFY_URI', config('global.sales_verify_uri'));

class EMailService extends Mailable {

    /**
     * 寄送郵件-業務驗證通知
     * @param type $md_contactmail
     * @param type $hash
     */
    public static function send_sales_verify($md_contactmail,$hash) {
        try {
            // \App\Library\CommonTools::writeErrorLogByMessage('con:'.$md_contactmail);
            // \App\Library\CommonTools::writeErrorLogByMessage('hash:'.$hash);
            $email = $md_contactmail;

            $salesVerifyUri = SALES_VERIFY_URI.$hash;
            // \App\Library\CommonTools::writeErrorLogByMessage($salesVerifyUri);
            $subject = '業務驗證通知';
            Mail::send('sales.mail_salesverify', compact('salesVerifyUri'), function ($message)use($subject, $email) {
                $message->to($email)
                        ->subject($subject);
            });
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

}
