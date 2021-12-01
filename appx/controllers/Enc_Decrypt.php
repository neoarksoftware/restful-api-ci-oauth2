<?php
/*
 *  Copyright (c) 2021 Neoark
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 */

require APPPATH . '/libraries/REST_Controller.php';

class Enc_Decrypt extends REST_Controller {

    public function __construct() { 
        parent::__construct(); 
    } //Constructor - Closed


   /* @param: Encryption and Decryption RESTFul API
    * @description: method POST
    * @date: November, 2021
    * @modify: 
    * @author: Neoark Software Pvt Team
    * @copyrights: Neoark
    */
    function encrypt_decrypt_post() {
        $this->encrypt_decrypt_method = stripslashes($this->post('encrypt_decrypt_method'));
        $this->encrypt_decrypt_str	= stripslashes($this->post('encrypt_decrypt_str'));
        $this->action 	= stripcslashes($this->post('action'));
        $this->usr_def_key 	= stripcslashes($this->post('usr_def_key'));
        $this->usr_def_secret 	= stripcslashes($this->post('usr_def_secret'));


        //Verifying & Generate expired access token - START
        $this->token_result = $this->verify_access_token();
        if($this->token_result === false ) {
            if($this->gen_token_via_refresh_token() === false ) {
                echo json_decode($this->response(array(
                    'status'    => false,
                    'message'   => 'Failed to generate access token though refresh token. ' . REPORT_ISU,
                    'access_token'  => '',
                    'refresh_token' => '',  
                ), 200));   
            } //else - covered in called - verify_access_token()
        } //Verifying & Generate expired access token - END

        //Validation - madatory parameters - START
        if(!isset($this->encrypt_decrypt_method) || $this->encrypt_decrypt_method == '') {
			echo json_decode($this->response(array(
				'status' 		=> false,
				'message' 		=> 'Missing encryption/decryption method',
				'access_token' 	=> $this->access_token,
				'refresh_token' => $this->refresh_token,
				'data'			=> array(),
			), 200)); 	
		}
        else if(!isset($this->encrypt_decrypt_str) || $this->encrypt_decrypt_str == '') {
			echo json_decode($this->response(array(
				'status' 		=> false,
				'message' 		=> 'Missing encryption/decryption string',
				'access_token' 	=> $this->access_token,
				'refresh_token' => $this->refresh_token,
				'data'			=> array(),
			), 200)); 	
		}
        else if(!isset($this->action) || $this->action == '') {
			echo json_decode($this->response(array(
				'status' 		=> false,
				'message' 		=> 'Missing action',
				'access_token' 	=> $this->access_token,
				'refresh_token' => $this->refresh_token,
				'data'			=> array(),
			), 200)); 	
		}
        else if(($this->action !== 'decryption' ) && ($this->action !== 'encryption')) {
            echo json_decode($this->response(array(
                'status'        => false,
                'message'       => 'Only `encryption` and `descryption` action supported',
                'access_token'  => $this->access_token,
                'refresh_token' => $this->refresh_token,
                'data'          => array(),
            ), 200));   
        }
        else if(!isset($this->usr_def_key) || $this->usr_def_key == '') {
			echo json_decode($this->response(array(
				'status' 		=> false,
				'message' 		=> 'Missing user defined key or salt',
				'access_token' 	=> $this->access_token,
				'refresh_token' => $this->refresh_token,
				'data'			=> array(),
			), 200)); 	
		}
        else if(!isset($this->usr_def_secret) || $this->usr_def_secret == '') {
			echo json_decode($this->response(array(
				'status' 		=> false,
				'message' 		=> 'Missing user defined secret',
				'access_token' 	=> $this->access_token,
				'refresh_token' => $this->refresh_token,
				'data'			=> array(),
			), 200)); 	
		}
        //Validation - madatory parameters - END
        
        $this->encrpt_dscrpt_rslts = $this->encryption_decryption($this->encrypt_decrypt_method, $this->encrypt_decrypt_str, $this->action, $this->usr_def_key, $this->usr_def_secret);
        if($this->encrpt_dscrpt_rslts === false ) { //Failed 'Encryption or 'Decryption'
            if($this->action == 'decryption') { //Required 'Decryption' failed
                echo json_decode($this->response(array(
                    'status' 		=> false,
                    'message' 		=> 'Invalid encrypted string',
                    'access_token' 	=> $this->access_token,
                    'refresh_token' => $this->refresh_token,
                    'data'          => array(
                            'passed_string' => $this->encrypt_decrypt_str,
                            'decrypted_string' => '',
                        ),
                ), 200));
            }
            else if($this->action == 'encryption') { //Required 'Encryption' failed
                echo json_decode($this->response(array(
                    'status' 		=> false,
                    'message' 		=> 'Failed to encrypt',
                    'access_token' 	=> $this->access_token,
                    'refresh_token' => $this->refresh_token,
                    'data'          => array(
                            'passed_string' => $this->encrypt_decrypt_str,
                            'encrypted_string' => '',
                        ),
                ), 200));
            }
        }
        else {
            if($this->action == 'decryption') { //'Dncryption' for passed value
                echo json_decode($this->response(array(
                    'status' 		=> true,
                    'message' 		=> 'Decryption done successfully',
                    'access_token' 	=> $this->access_token,
                    'refresh_token' => $this->refresh_token,
                    'data'          => array(
                            'passed_string' => $this->encrypt_decrypt_str,
                            'decrypted_string' => $this->encrpt_dscrpt_rslts,
                        ),
                ), 200));
            }
            else if($this->action == 'encryption') { //'Encryption' for passed value
                 echo json_decode($this->response(array(
                    'status' 		=> true,
                    'message' 		=> 'Encryption done successfully',
                    'access_token' 	=> $this->access_token,
                    'refresh_token' => $this->refresh_token,
                    'data'          => array(
                            'passed_string' => $this->encrypt_decrypt_str,
                            'encrypted_string' => $this->encrpt_dscrpt_rslts,
                        ),
                ), 200));
            }
            else { //Passed other than `enctyption` or `decryption
                 echo json_decode($this->response(array(
                    'status' 		=> false,
                    'message' 		=> 'Only `enctyption` and `decryption` action is supported',
                    'access_token' 	=> $this->access_token,
                    'refresh_token' => $this->refresh_token,
                    'data'          => array(
                            'passed_string' => $this->encrypt_decrypt_str,
                            'encrypted_string' => '',
                        ),
                ), 200));
            }
        }
    } //Function - Closed


   /* @param: Encryption and Decryption of passed value
    * @description: 
    * @date: November, 2021
    * @modify: 
    * @author: Neoark Software Pvt Team
    * @copyrights: Neoark
    */
    function encryption_decryption($method, $encrpt_dscrpt_str, $action, $usr_key, $usr_secret) {
        $this->encrypt_decrypt_method = $method;
        $this->encrypt_decrypt_str = $encrpt_dscrpt_str;
        $this->action = $action;
        $this->usr_def_key = $usr_key;
        $this->usr_def_secret = $usr_secret;
        $this->result = false; //For addressing notices

        $this->enc_key = hash('sha256', $this->usr_def_key);
        $this->hash_hmac_algo = substr(hash('sha256', $this->usr_def_secret), 0, 16); // sha256 is hash_hmac_algo
        if ($this->action == 'encryption') { //Encryption - START
            $this->result = openssl_encrypt($this->encrypt_decrypt_str, $this->encrypt_decrypt_method, $this->enc_key, 0, $this->hash_hmac_algo);
            $this->result = base64_encode($this->result);
        } //Encryption - END

        else if ($this->action == 'decryption') { //Decryption - START
            $this->result = openssl_decrypt(base64_decode($this->encrypt_decrypt_str), $this->encrypt_decrypt_method, $this->enc_key, 0, $this->hash_hmac_algo);
        } //Decryption - END

        return $this->result;
    } //Function - Closed


   /* @param: Encryption and Decryption of passed value
    * @description: 
    * @date: November, 2021
    * @modify: 
    * @author: Neoark Software Pvt Team
    * @copyrights: Neoark
    */
    function encrypt_decrypt_url_post() {
        $this->encrypt_decrypt_method = stripslashes($this->post('encrypt_decrypt_method'));
        $this->encrypt_decrypt_str = stripslashes($this->post('encrypt_decrypt_str'));
        $this->action = stripcslashes($this->post('action'));
        $this->usr_def_key = stripcslashes($this->post('usr_def_key'));
        $this->usr_def_secret = stripcslashes($this->post('usr_def_secret'));
        $this->encrpt_dscrpt_rslts = $this->encryption_decryption($this->encrypt_decrypt_method, $this->encrypt_decrypt_str, $this->action, $this->usr_def_key, $this->usr_def_secret);
        var_dump($this->encrpt_dscrpt_rslts);
    } //Function - Closed

} //Class - Closed
?>