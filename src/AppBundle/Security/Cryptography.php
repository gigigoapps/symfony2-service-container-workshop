<?php

namespace AppBundle\Security;

class Cryptography
{
    private $key;
    private $algorithm;
    private $mode;

    /**
     * @param string $key Key used as base
     * @param string $algorithm Algorithm name to use. See mcrypt_module_open function
     * @param string $mode Mode to use. See mcrypt_module_open function
     */
    public function __construct($key, $algorithm, $mode)
    {
        $this->key = $key;
        $this->algorithm = $algorithm;
        $this->mode = $mode;
    }

    /**
     * Encrypt data
     *
     * @param string $data Data to encrypt
     *
     * @return string
     */
    public function encrypt($data)
    {
        $key = $this->key;
        $algorithm = $this->algorithm;
        $mode = $this->mode;

        // open descriptor
        $td = mcrypt_module_open($algorithm, '', $mode, '');

        // get key fitted to correct size
        $key = $this->fitKey($key, $td);

        // generate random iv with correct size according descriptor
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td));

        // init descriptor
        mcrypt_generic_init($td, $key, $iv);

        // encrypt data
        $encrypted = mcrypt_generic($td, $data);

        // close descriptor
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        /**
         * Always return iv concat with encrypted data to use in description process
         */
        return $iv.$encrypted;
    }

    /**
     * Decrypt data
     *
     * @param string $data Data to decrypt
     *
     * @return string
     */
    public function decrypt($data)
    {
        $key = $this->key;
        $algorithm = $this->algorithm;
        $mode = $this->mode;

        // open descriptor
        $td = mcrypt_module_open($algorithm, '', $mode, '');

        // get key fitted to correct size
        $key = $this->fitKey($key, $td);

        // get iv from encrypted data (iv was concatenated with data)
        $ivSize = mcrypt_enc_get_iv_size($td);  // get iv size
        $iv = substr($data, 0, $ivSize);        // cut first part (this is the iv used in encryption process)
        $data = substr($data, $ivSize);         // cut second part (this is de encrypted data)

        // init descriptor
        mcrypt_generic_init($td, $key, $iv);

        // decrypt data
        $decrypted = mdecrypt_generic($td, $data);

        // close descriptor
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        // remove padding data to adjust algorithm block size (null by default)
        return trim($decrypted);
    }

    /**
     * Get correct key for a descriptor adjusting size to correct number of bits
     *
     * @param string $key Plain key
     * @param resource $td Descriptor
     *
     * @return string
     */
    private function fitKey($key, $td)
    {
        // get key size for descriptor
        $ks = mcrypt_enc_get_key_size($td);

        // build big key with 1-byte characters
        $key1 = md5($key);
        $key2 = md5($key1);
        $key = substr($key1, 0, $ks/2) . substr(strtoupper($key2), (round(strlen($key2) / 2)), $ks/2);

        // cut to correct size
        return substr($key.$key1.$key2.strtoupper($key1),0,$ks);
    }
}