<?php if( !defined('WPINC') ) die;
/**
 * PaypalIPN class
 */

class PaypalIPN {

    private $_use_sandbox = false;
    private $_use_local_certs = true;

    /**
     * PayPal IPN postback endpoints
     */
    const VERIFY_URI = 'https://ipnpb.paypal.com/cgi-bin/webscr';
    const SANDBOX_VERIFY_URI = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
    /**
     * Possible responses from PayPal after the request is issued.
     */
    const VALID = 'VERIFIED';
    const INVALID = 'INVALID';

    public function __construct($use_sandbox = false, $use_local_certs = true) {

        $this->_use_sandbox = !!$use_sandbox;
        $this->_use_local_certs = !!$use_local_certs;

    }

    /**
     * Sets the IPN verification to sandbox mode (for use when testing,
     * should not be enabled in production).
     * @return void
     */
    public function useSandbox() {
        $this->_use_sandbox = true;
    }
    /**
     * Determine endpoint to post the verification data to.
     * @return string
     */
    public function getPaypalUri()
    {
        if ($this->_use_sandbox) {
            return self::SANDBOX_VERIFY_URI;
        } else {
            return self::VERIFY_URI;
        }
    }
    /**
     * Verification Function
     * Sends the incoming post data back to paypal using the cURL library.
     *
     * @return bool
     * @throws Exception
     */
    public function verifyIPN()
    {
        if ( !count($_POST) ) {
            return 'Missing POST Data while processing IPN';
        }

        $myPost = array();
        foreach (explode('&', file_get_contents('php://input')) as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                // Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
                if ($keyval[0] === 'payment_date') {
                    if (substr_count($keyval[1], '+') === 1) {
                        $keyval[1] = str_replace('+', '%2B', $keyval[1]);
                    }
                }
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }
        // Build the body of the verification post request, adding the _notify-validate command.
        $req = 'cmd=_notify-validate';
        $get_magic_quotes_exists = false;
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }
        // Post the data back to paypal, using curl. Throw exceptions if errors occur.
        // phpcs:disable
        $ch = curl_init($this->getPaypalUri());
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        // This is often required if the server is missing a global cert bundle, or is using an outdated one.
        if ($this->_use_local_certs) {
            curl_setopt($ch, CURLOPT_CAINFO, __DIR__.'/cacert.pem');
        }

        $res = curl_exec($ch);
        $info = curl_getinfo($ch);
        $http_code = $info['http_code'];
        if ($http_code != 200) {
            return "PayPal responded with wrong HTTP code ($http_code) while processing IPN";
        }
        if ( !$res ) {

            curl_close($ch);
            return "cURL error while processing IPN: [".curl_errno($ch)."] ".curl_error($ch);

        }
        curl_close($ch);
        // phpcs:enable

        if($res != self::VALID) { // PayPal didn't verified the request
            return "Error while processing IPN: PayPal didn't verified the handshake answer";
        } else {
            return true;
        }

    }

}