<?php
/**
 * Created by PhpStorm.
 * User: Aga
 * Date: 16/4/2019
 * Time: 6:44 μμ
 */

namespace Apto\Msg91;


class MobileVerificationService
{
    const AUTH_KEY  = '272698AVxllpjwnL5cb5dc7a';
    const MESSAGE   = 'Dear user,%0a%23%23OTP%23%23 is your OTP for verification of your mobile number at Pointlocals Website.%0aFor More info - visit https://pointlocals.com.%0aThank You.';

    public function __construct() {

    }


    public function sendOTPMessage( $mobile ){

        $sentCode = false;
        $endpoint = "http://control.msg91.com/api/sendotp.php?otp_length=6&authkey=".self::AUTH_KEY."&message=".str_replace(' ','%20',self::MESSAGE)."&sender=POINTL&mobile=$mobile&otp_expiry=6&DLT_TE_ID=1207161404642541584";
        $response = $this->sendRequest( $endpoint );

        if( $response && isset( $response['type'] ) && $response['type'] === 'success' ){

            $sentCode = true;

        }
        return $sentCode;

    }


    public function verifyOTP( $mobile, $otp ) {

        $verified = false;
        $endpoint = "https://control.msg91.com/api/verifyRequestOTP.php?authkey=".self::AUTH_KEY."&mobile=$mobile&otp=$otp";
        $response = $this->sendRequest( $endpoint );

        if( $response && isset( $response['type'] ) && $response['type'] === 'success' ){

            $verified = true;

        }

        return $verified;

    }



    public function sendRequest( $endpoint ){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL             => $endpoint,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => "",
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_SSL_VERIFYPEER  => 0,
            CURLOPT_HTTPHEADER      => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));


        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        curl_close($curl);

        if ( !$err ) {

            $response = json_decode( $response, true );

        }
        else {

            $response = false;

        }

        return $response;

    }
}