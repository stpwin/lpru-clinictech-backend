<?php
include("../../verify/jwt.php");

class FirebaseToken
{
    private $project_id;
    private $public_key;
    private $jwtInstance;

    public function __construct(){
        $pkeys_raw = file_get_contents("../../config/firebase-adminsdk.json");
        $pkeys = json_decode($pkeys_raw, true);
        $private_key = $pkeys['private_key'];
        $this->project_id = $pkeys['project_id'];

        $private = openssl_pkey_get_private($private_key);
        $details = openssl_pkey_get_details($private);
        $this->public_key = $details['key'];

        $this->jwtInstance = new OAuth2_Encryption_JWT();
    }

    private function bool2Str($b){
        return $b ? 'true' : 'false';
    }
    

    public function verify($idToken){
        $decoded = $this->jwtInstance->decode($idToken, $this->public_key, false);
        $iss = $decoded["iss"];
        $exp = $decoded["exp"];
        $iat = $decoded["iat"];
        $aud = $decoded["aud"];
        $sub = $decoded["sub"];

        $exp_is_valid = isset($exp) && $exp > time();
        $iat_is_valid = isset($iat) && $iat < time();
        $aud_is_valid = isset($aud) && $aud == $this->project_id;
        $iss_is_valid = isset($iss) && $iss === "https://securetoken.google.com/".$this->project_id;

        // $exp_is_valid = $this->bool2Str($exp_is_valid);
        // $iat_is_valid = $this->bool2Str($iat_is_valid);
        // $aud_is_valid = $this->bool2Str($aud_is_valid);
        // $iss_is_valid = $this->bool2Str($iss_is_valid);

        // print_r("project_id: $this->project_id\n");
        // print_r("public_key: $this->public_key\n");

        // print_r("exp: $exp\n");
        // print_r("iat: $iat\n");
        // print_r("aud: $aud\n");
        // print_r("iss: $iss\n");

        // print_r("exp_is_valid: $exp_is_valid\n");
        // print_r("iat_is_valid: $iat_is_valid\n");
        // print_r("aud_is_valid: $aud_is_valid\n");
        // print_r("iss_is_valid: $iss_is_valid\n");

        return ($exp_is_valid && $iat_is_valid && $aud_is_valid && $iss_is_valid);
    }


}