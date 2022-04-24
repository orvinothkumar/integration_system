<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RestApiController extends Controller
{

    private function generateUniquePIN()
    {
        $regenerateNumber = true;
        do {
            $ranNumber = rand(100000, 999999);
            $query = "SELECT * FROM users WHERE otp = '$ranNumber'";
            $results = DB::select($query);
            $total = count($results);
            if ($total == 0) {
                $regenerateNumber = false;
            }
        } while ($regenerateNumber);
        return $ranNumber;
    }

    private function validateUserId($userUUID)
    {
        $query = "SELECT id FROM `users` where id='$userUUID' and status=1 LIMIT 1";
        $results = DB::select($query);
        return $results;
    }

    public function signUpOtp(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "emailAddress" => "required",
            "firstName" => "required",
            "lastName" => "required",
            "birthDate" => "required",
            "mobileNumber" => "required",
            "username" => "required",
            "password" => "required",
            "type" => "required",
            "address" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $emailAddress = $req->emailAddress;
            $firstName = $req->firstName;
            $lastName = $req->lastName;
            $birthDate = $req->birthDate;
            $mobileNumber = $req->mobileNumber;
            $username = $req->username;
            $password = $req->password;
            $type = $req->type;
            $address = $req->address;
            $otp = $this->generateUniquePIN();

            $query = "SELECT * FROM `users` WHERE (email='$emailAddress' OR username='$username')";
            $results = DB::select($query);
            $total = count($results);

            if ($total == 0) {
                try {
                    $Insertid = User::create([
                        'name' => trim($firstName . ' ' . $lastName),
                        'first_name' => trim($firstName),
                        'last_name' => trim($lastName),
                        'username' => $username,
                        'user_type' => $type,
                        'email' => $emailAddress,
                        'mobile' => $mobileNumber,
                        'dob' => $birthDate,
                        'otp' => $otp,
                        'address' => $address,
                        'validity_date' => date('Y-m-d H:i:s', strtotime('+1 year')),
                        'password' => Hash::make($password),
                        'role_id' => 2,
                        'created_by' => 1,
                        'is_verified' => 0,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                    ])->id;

                    if ($Insertid > 0) {
                        // $hashed_token = password_hash($Insertid, PASSWORD_BCRYPT, array('cost' => 5));
                        $data = [
                            'subject' => 'OTP Verification Email',
                            'email' => $req->emailAddress,
                            'content' => 'OTP Verification Code is : ' . $otp,
                        ];

                        Mail::to($data['email'])->send(new SendMail($data));
                        $response = array('status' => 'success', 'message' => 'An account has been created for ' . $emailAddress . ' successfully. Please check your email for OTP verification');
                        $responseCode = 200;
                    } else {
                        $response = array('status' => 'error', "message" => "Error on signing up");
                        $responseCode = 200;
                    }
                } catch (QueryException | \Exception $e) {
                    $response = array('status' => 'error', "message" => "Error on signing up", "errors" => $e->getMessage());
                    $responseCode = 200;
                }
            } else {
                if ($results[0]->is_verified == 1) {
                    $response = array('status' => 'error', 'message' => 'EmailAddress/Username already exists!');
                    $responseCode = 200;
                } else {
                    try {
                        $updated = DB::table('users')
                            ->where('id', $results[0]->id)
                            ->update([
                                'name' => trim($firstName . ' ' . $lastName),
                                'first_name' => trim($firstName),
                                'last_name' => trim($lastName),
                                'username' => $username,
                                'user_type' => $type,
                                'email' => $emailAddress,
                                'mobile' => $mobileNumber,
                                'dob' => $birthDate,
                                'otp' => $otp,
                                'address' => $address,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);

                        if ($updated) {
                            $data = array();
                            $data['userUUID'] = $results[0]->id;
                            $data['fullName'] = trim($firstName . ' ' . $lastName);
                            $data['emailAddress'] = $emailAddress;
                            $data = [
                                'subject' => 'OTP Verification Email',
                                'email' => $req->emailAddress,
                                'content' => 'OTP Verification Code is : ' . $otp,
                            ];

                            Mail::to($data['email'])->send(new SendMail($data));
                            $response = array('status' => 'success', 'message' => 'An account has been created for ' . $emailAddress . ' successfully. Please check your email for OTP verification', 'data' => $data);
                            $responseCode = 200;
                        } else {
                            $response = array('status' => 'error', "Error on signing up");
                            $responseCode = 200;
                        }
                    } catch (QueryException | \Exception $e) {
                        $response = array('status' => 'error', "message" => "Error on signing up", "errors" => $e->getMessage());
                        $responseCode = 200;
                    }
                }
            }
        }
        return response()->json($response, $responseCode);
    }

    public function signUp(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "emailAddress" => "required",
            "firstName" => "required",
            "lastName" => "required",
            "birthDate" => "required",
            "mobileNumber" => "required",
            "username" => "required",
            "password" => "required",
            "type" => "required",
            "address" => "required",
            "otp" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $emailAddress = $req->emailAddress;
            $firstName = $req->firstName;
            $lastName = $req->lastName;
            $birthDate = $req->birthDate;
            $mobileNumber = $req->mobileNumber;
            $username = $req->username;
            $password = $req->password;
            $type = $req->type;
            $address = $req->address;
            $otp = $req->otp;

            $query = "SELECT id FROM `users` WHERE (email='$emailAddress' AND otp='$otp')";
            $results = DB::select($query);
            $total = count($results);

            if ($total > 0) {
                try {
                    $updated = DB::table('users')
                        ->where('id', $results[0]->id)
                        ->update([
                            'name' => trim($firstName . ' ' . $lastName),
                            'first_name' => trim($firstName),
                            'last_name' => trim($lastName),
                            'username' => $username,
                            'user_type' => $type,
                            'email' => $emailAddress,
                            'mobile' => $mobileNumber,
                            'dob' => $birthDate,
                            'address' => $address,
                            'otp' => '',
                            'is_verified' => 1,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    if ($updated) {
                        $data = array();
                        $data['userUUID'] = $results[0]->id;
                        $data['fullName'] = trim($firstName . ' ' . $lastName);
                        $data['emailAddress'] = $emailAddress;
                        $response = array('status' => 'success', 'message' => 'Signed up successfully.', 'data' => $data);
                        $responseCode = 200;
                    } else {
                        $response = array('status' => 'error', "Error on OTP verification");
                        $responseCode = 200;
                    }
                } catch (QueryException | \Exception $e) {
                    $response = array('status' => 'error', "message" => "Error on OTP verification", "errors" => $e->getMessage());
                    $responseCode = 200;
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid OTP!');
                $responseCode = 200;
            }
        }
        return response()->json($response, $responseCode);
    }

    public function resendSignupOtp(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "emailAddress" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $emailAddress = $req->emailAddress;
            $otp = $this->generateUniquePIN();

            $query = "SELECT * FROM `users` WHERE (email='$emailAddress' AND is_verified = 0)";
            $results = DB::select($query);
            $total = count($results);

            if ($total > 0) {
                try {
                    $updated = DB::table('users')
                        ->where('id', $results[0]->id)
                        ->update([
                            'otp' => $otp,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    if ($updated) {
                        $data = [
                            'subject' => 'OTP Verification Email',
                            'email' => $req->emailAddress,
                            'content' => 'OTP Verification Code is : ' . $otp,
                        ];

                        Mail::to($data['email'])->send(new SendMail($data));
                        $response = array('status' => 'success', 'message' => 'OTP was sent to ' . $req->emailAddress . ' successfully.');
                        $responseCode = 200;
                    } else {
                        $response = array('status' => 'error', "Error on resend otp");
                        $responseCode = 200;
                    }
                } catch (QueryException | \Exception $e) {
                    $response = array('status' => 'error', "message" => "Error on resend otp", "errors" => $e->getMessage());
                    $responseCode = 200;
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid EmailAddress/Username/MobileNumber');
                $responseCode = 200;
            }
        }
        return response()->json($response, $responseCode);
    }

    public function signIn(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "password" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $emailAddress = '';
            if ($req->emailAddress != '') {
                $emailAddress = $req->emailAddress;
            }
            $mobileNumber = '';
            if ($req->mobileNumber != '') {
                $mobileNumber = $req->mobileNumber;
            }
            $username = '';
            if ($req->username != '') {
                $username = $req->username;
            }
            $password = $req->password;

            $query = "SELECT * FROM `users` WHERE (email = '$emailAddress' OR username = '$username' OR mobile = '$mobileNumber') AND role_id = 2";
            $results = DB::select($query);
            $total = count($results);

            if ($total > 0) {
                if (Hash::check($req->password, $results[0]->password)) {
                    // $hashed_token = password_hash($results[0]->id, PASSWORD_BCRYPT, array('cost' => 5));
                    $data = array();
                    $data['userUUID'] = $results[0]->id;
                    $data['emailAddress'] = $results[0]->email;
                    $data['fullName'] = $results[0]->first_name . ' ' . $results[0]->last_name;
                    $data['birthDate'] = $results[0]->dob;
                    $data['mobileNumber'] = $results[0]->mobile;
                    $data['username'] = $results[0]->username;
                    $data['type'] = $results[0]->user_type;
                    $data['Address'] = $results[0]->address;
                    $response = array('status' => 'success', 'message' => 'Logged in successfully', 'data' => $data);
                    $responseCode = 200;
                } else {
                    $response = array('status' => 'error', 'message' => 'Invalid Password');
                    $responseCode = 200;
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid Email/Username/Mobile or Password');
                $responseCode = 200;
            }
        }
        return response()->json($response, $responseCode);
    }

    public function forgotOtp(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "emailAddress" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $query = "SELECT * FROM `users` WHERE email='$req->emailAddress' AND is_verified = 1";
            $results = DB::select($query);
            $uCount = count($results);
            if ($uCount > 0) {
                $otp = $this->generateUniquePIN();
                try {
                    $updated = DB::table('users')
                        ->where('id', $results[0]->id)
                        ->update([
                            'otp' => $otp,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    if ($updated) {
                        $data = [
                            'subject' => 'OTP Verification Email',
                            'email' => $req->emailAddress,
                            'content' => 'OTP Verification Code is : ' . $otp,
                        ];

                        Mail::to($data['email'])->send(new SendMail($data));
                        $response = array('status' => 'success', 'message' => 'OTP was sent to ' . $req->emailAddress . ' successfully.');
                        $responseCode = 200;
                    } else {
                        $response = array('status' => 'error', "Error on OTP verification");
                        $responseCode = 200;
                    }
                } catch (QueryException | \Exception $e) {
                    $response = array('status' => 'error', "message" => "Error on OTP verification", "errors" => $e->getMessage());
                    $responseCode = 200;
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid Email Address');
                $responseCode = 200;
            }
        }
        return response()->json($response, $responseCode);
    }

    public function resendForgotOtp(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "emailAddress" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $emailAddress = $req->emailAddress;
            $otp = $this->generateUniquePIN();

            $query = "SELECT * FROM `users` WHERE (email='$emailAddress' AND is_verified = 1)";
            $results = DB::select($query);
            $total = count($results);

            if ($total > 0) {
                try {
                    $updated = DB::table('users')
                        ->where('id', $results[0]->id)
                        ->update([
                            'otp' => $otp,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    if ($updated) {
                        $data = [
                            'subject' => 'OTP Verification Email',
                            'email' => $req->emailAddress,
                            'content' => 'OTP Verification Code is : ' . $otp,
                        ];

                        Mail::to($data['email'])->send(new SendMail($data));
                        $response = array('status' => 'success', 'message' => 'OTP was sent to ' . $req->emailAddress . ' successfully.');
                        $responseCode = 200;
                    } else {
                        $response = array('status' => 'error', "Error on resend otp");
                        $responseCode = 200;
                    }
                } catch (QueryException | \Exception $e) {
                    $response = array('status' => 'error', "message" => "Error on resend otp", "errors" => $e->getMessage());
                    $responseCode = 200;
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid Email Address');
                $responseCode = 200;
            }
        }
        return response()->json($response, $responseCode);
    }

    public function validateForgotOtp(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "emailAddress" => "required",
            "otp" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $emailAddress = $req->emailAddress;
            $otp = $req->otp;

            $query = "SELECT * FROM `users` WHERE (email='$emailAddress' AND otp='$otp')";
            $results = DB::select($query);
            $total = count($results);

            if ($total > 0) {
                $response = array('status' => 'success', 'message' => 'valid OTP');
                $responseCode = 200;
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid OTP');
                $responseCode = 200;
            }
        }
        return response()->json($response, $responseCode);
    }

    public function resetPassword(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "emailAddress" => "required",
            "otp" => "required",
            "password" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $emailAddress = $req->emailAddress;
            $otp = $req->otp;
            $password = $req->password;

            $query = "SELECT * FROM `users` WHERE (email='$emailAddress' AND otp='$otp')";
            $results = DB::select($query);
            $total = count($results);

            if ($total > 0) {
                $user_id = $results[0]->id;

                try {
                    $updated = DB::table('users')
                        ->where('id', $user_id)
                        ->update([
                            'otp' => '',
                            'password' => Hash::make($password),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    if ($updated) {
                        $response = array('status' => 'success', 'message' => 'Password changed successfully');
                        $responseCode = 200;
                    } else {
                        $response = array('status' => 'error', "message" => "Error on reset password");
                        $responseCode = 200;
                    }
                } catch (QueryException | \Exception $e) {
                    $response = array('status' => 'error', "message" => "Error on reset password", "errors" => $e->getMessage());
                    $responseCode = 200;
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid Email/OTP');
                $responseCode = 200;
            }
        }
        return response()->json($response, $responseCode);
    }

    public function userProfile(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "userUUID" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $userUUID = $req->userUUID;

            $query = "SELECT * FROM `users` WHERE (id = '$userUUID')";
            $results = DB::select($query);
            $total = count($results);

            if ($total > 0) {
                $data = array();
                $data['userUUID'] = $results[0]->id;
                $data['emailAddress'] = $results[0]->email;
                $data['firstName'] = $results[0]->first_name;
                $data['lastName'] = $results[0]->last_name;
                $data['birthDate'] = $results[0]->dob;
                $data['mobileNumber'] = $results[0]->mobile;
                $data['username'] = $results[0]->username;
                $data['type'] = $results[0]->user_type;
                $data['Address'] = $results[0]->address;
                $response = array('status' => 'success', 'data' => $data);
                $responseCode = 200;
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid Email/Username/Mobile or Password');
                $responseCode = 200;
            }
        }
        return response()->json($response, $responseCode);
    }

    public function updateProfile(Request $req)
    {
        $response = array();
        $responseCode = 500;
        $rules = array(
            "userUUID" => "required",
            "firstName" => "required",
            "lastName" => "required",
            "mobileNumber" => "required",
            "birthDate" => "required",
            "address" => "required",
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            $response = array('status' => 'error', 'message' => 'Invalid parameters', "errors" => $validator->errors());
            $responseCode = 400;
        } else {
            $userUUID = $req->userUUID;
            $firstName = $req->firstName;
            $lastName = $req->lastName;
            $mobileNumber = $req->mobileNumber;
            $birthDate = $req->birthDate;
            $address = $req->address;

            $userRes = $this->validateUserId($userUUID);
            $uCount = count($userRes);
            if ($uCount > 0) {
                try {
                    $updated = DB::table('users')
                        ->where('id', $userUUID)
                        ->update([
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'mobile' => $mobileNumber,
                            'dob' => $birthDate,
                            'address' => $address,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    if ($updated) {
                        $response = array('status' => 'success', 'message' => 'User profile details updated successfully');
                        $responseCode = 200;
                    } else {
                        $response = array('status' => 'error', "message" => "Error on updating profile details");
                        $responseCode = 200;
                    }
                } catch (QueryException | \Exception $e) {
                    $response = array('status' => 'error', "message" => "Error on updating profile details", "errors" => $e->getMessage());
                    $responseCode = 200;
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid User ID');
                $responseCode = 200;
            }
        }
        return response()->json($response, $responseCode);
    }

}
