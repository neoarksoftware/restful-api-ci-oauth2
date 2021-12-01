MIT License

Copyright (c) 2021 Neoark

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


 
 Development Environment & Disclaimer!
 -------------------------------------
 _It is an open source SAMPLE SCRIPT and the used development environment is `PHP-7.4 (or higher) - Codeigniter version-3.x or higher` (an MVC framework: https://codeigniter.com/download and codeigniter document: https://codeigniter.com/user_guide/index.html ) `Mysql Apache2, Linux (Ubuntu-18.04 OR Ubuntu-20.04), JSON support, setup Oauth-v-2.0` (see more: https://bshaffer.github.io/oauth2-server-php-docs ) etc, however, author neither warranty nor recommend the script for the stagging and/or for the production development environment._ 
 
 * @package     SAMPLE SCRIPT
 * @author      Development Team @Neoark Software Pvt Ltd
 * @license     http://opensource.org/licenses/MIT
 * @questions:  http://www.support.gowithexperts.com
 * @link        http://www.neoarks.com
 * @since       Version 1.0.0
 * @filesource
 ------------------
 

🙌 Prerequisites:
----------------
The prerequisites `(PHP-7.4 (or higher) -Codeigniter (MVC framework), Mysql Apache2, Linux (Ubuntu-18.04 OR Ubuntu-20.04), JSON support, Git, Browser, setup Oauth-v-2.0` (see more: https://bshaffer.github.io/oauth2-server-php-docs ), etc) must installed already on the development environment. If these prerequisites are not available yet, please install them and then proceed below mentioned Installation steps.


Installation Steps/Guide:
------------------------
1. cd /var/www/html/                                                            [Move to DocumentRoot directory]
2. git clone https://github.com/neoarksoftware/restful-api-ci-oauth2.git 		[OR Download `main` branch code]
2. cd restful-api-ci-oauth2


Use Talend API Tester(Google Chrome Extension) (see here: https://chrome.google.com/webstore/detail/talend-api-tester-free-ed/aejoelaoggembcahagimdiliamlcdmfm?hl=en ) OR Postman (see here: https://www.postman.com ) OR any other your favorite API tester for testing below paramerter based endpoint/APIs. Directory `oauth2` need to setup on rootDirectory (ie /var/www/html/oauth2)

Get Authorization Code:
-----------------------
```
URL: http://localhost/oauth2/authorize.php?response_type=code&client_id=09eb2bec2e45086aeb3e595c7b0302f2&state=xyz
Method: GET
Headers: {}
Request: {}
Response: 
{
  "authorization_code": "2e88be6fcae6778f27dcdc9d9f657acc18e41ea0"
}
```


Oauth Token API
----------------
```
URL: http://localhost/oauth2/token.php
Method: POST
Headers: 
{
  "Content-Type": "application/x-www-form-urlencoded"
}
Request: 
{
  "grant_type": "authorization_code",
  "client_id": "09eb2bec2e45086aeb3e595c7b0302f2",
  "client_secret": "aae5b5121908536a99e2054719bb6999",
  "code": "2e88be6fcae6778f27dcdc9d9f657acc18e41ea0"
}
Response: 
{
  "access_token": "326ad2283271e2fff7a547d1c4d2a845ac7d6188",
  "expires_in": 3600,
  "token_type": "Bearer",
  "scope": null,
  "refresh_token": "df115f04a0520c423ac4047518dc2aa6e1b0e5c0"
}
```

Encryption API 
------------------
```
URL: http://localhost/restful-api-ci-oauth2/index.php/Enc_Decrypt/encrypt_decrypt
Method: POST
Request parameters:
{
  "encrypt_decrypt_method": "aes-256-cbc",
  "encrypt_decrypt_str": "Neoarks",
  "action": "encryption",
  "usr_def_key": "neo",
  "usr_def_secret": "aRks"
}

Response:
{
  "status": true,
  "message": "Encryption done successfully",
  "access_token": "326ad2283271e2fff7a547d1c4d2a845ac7d6188",
  "refresh_token": "df115f04a0520c423ac4047518dc2aa6e1b0e5c0",
  "data": {
    "passed_string": "Neoarks",
    "encrypted_string": "MWJPOGtlQUhXQW5zVjhocFBIQWFJdz09"
  }
}
```
Decryption API
---------
```
URL: http://localhost/restful-api-ci-oauth2/index.php/Enc_Decrypt/encrypt_decrypt
Method: POST
Request: 
{
  "encrypt_decrypt_method": "aes-256-cbc",
  "encrypt_decrypt_str": "MWJPOGtlQUhXQW5zVjhocFBIQWFJdz09",
  "action": "decryption",
  "usr_def_key": "neo",
  "usr_def_secret": "aRks"
}

Response:
{
  "status": true,
  "message": "Decryption done successfully",
  "access_token": "326ad2283271e2fff7a547d1c4d2a845ac7d6188",
  "refresh_token": "df115f04a0520c423ac4047518dc2aa6e1b0e5c0",
  "data": {
    "passed_string": "MWJPOGtlQUhXQW5zVjhocFBIQWFJdz09",
    "decrypted_string": "Neoarks"
  }
}
```