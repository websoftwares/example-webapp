<?php

namespace Websoftwares\Domain;

/**
 * RandomNumber.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class RandomString
{
    /**
     * base64urlEncode.
     *
     * @param string $string
     *
     * @return string
     */
    public function base64urlEncode($string)
    {
        //encode randomkey to base64 url
        return rtrim(strtr(base64_encode($string), '+/', '-_'), '=');
    }

    /**
     * base64urlDecode.
     *
     * @param string $string
     *
     * @return string
     */
    public function base64urlDecode($string)
    {
        //decode randomkey to base64 url
        return base64_decode(str_pad(strtr($string, '-_', '+/'), strlen($string) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * generate.
     *
     * @param int  $bitLength
     * @param bool $encode
     *
     * @return mixed
     */
    public function generate($bitLength = 128, $encode = false)
    {
        // nix type systems only /dev/random is a little slower then /dev/urandom
        // Why would we want to run on a Wintendo anyway :-)
        $fp = @fopen('/dev/urandom', 'rb');

        if ($fp !== false) {
            $randomString = substr(base64_encode(@fread($fp, ($bitLength + 7) / 8)), 0, (($bitLength + 5) / 6)  - 2);
            @fclose($fp);
            // return random generated string
            return ! $encode ? $randomString : $this->base64urlEncode($randomString);
        }

        return false;
    }
}
