<?php

namespace Ambta\DoctrineEncryptBundle\Encryptors;

/**
 * Class for variable encryption
 * 
 * @author Marcel van Nuil <marcel@ambta.com>
 */
class Rijndael128Encryptor implements EncryptorInterface {

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $initializationVector;

    /**
     * {@inheritdoc}
     */
    public function __construct($key) {
        $this->secretKey = md5($key);
        $this->initializationVector = mcrypt_create_iv(
            mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB),
            MCRYPT_RAND
        );
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data) {

        if(is_string($data)) {
            $test = mcrypt_encrypt(
                MCRYPT_RIJNDAEL_128,
                $this->secretKey,
                $data,
                MCRYPT_MODE_ECB,
                $this->initializationVector
            );
            return trim(base64_encode($test)). "<ENC>";
        }

        return $data;

    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data) {

        if(is_string($data)) {

            $data = str_replace("<ENC>", "", $data);

            return trim(mcrypt_decrypt(
                MCRYPT_RIJNDAEL_128,
                $this->secretKey,
                base64_decode($data),
                MCRYPT_MODE_ECB,
                $this->initializationVector
            ));
        }

        return $data;
    }
}
