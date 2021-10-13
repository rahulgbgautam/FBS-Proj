<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;
use Auth;
use Socialite;
use Carbon\Carbon;

class AuthController extends Controller
{	

    public function signUp(Request $request){
        $validator = Validator::make($request->all(), [
	        'name'=>'required|max:50',
	        'email'=>'required|email|unique:users',
	        'phone'=>'required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:8|max:15',
	        'dob'=>'required|date',
	        'gender'=>'required',
	        'password'=>'required|min:6',
	        'confirm_password'=>'required|same:password'
		]);

        if(!$validator->fails()){
        	if($request->password === $request->confirm_password){
        		$token = Str::random(30);
        		$otp = 1111;
	        	$data = new User;
	        	$data->name = $request->name;
	        	$data->email = $request->email;
	        	$data->phone = $request->phone;
	        	$data->dob = $request->dob;
	        	$data->gender = $request->gender;
	        	$data->device_type = $request->device_type;
	        	$data->device_token = $request->device_token;
	        	$data->password = Hash::make($request->password);
	            $data->remember_token = $token;
	            $data->otp = $otp;
	            $data->otp_generated_at = date("Y-m-d H:i:s");
            	$data->save();
                $verification_url = url('/api/email-verification',$token);
            	$emailData['name']  = $request->name;
                $emailData['email'] = $request->email;
                $emailData['link'] = $verification_url;
                sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'userRegistrationMail',$emailData);
            	return response()->json([
					'status' => true,
					'message' => "User registered successfully and verification email has been sent to the email, please verify",
					'data' => $data,

				]);
            }	
        }else{
        		return response()->json([
					'status' => false,
					'message' =>$validator->messages()->first(),
					'data' => 'null'
				]);
    		}
    		
    }
    public function login(Request $request){

    	if(!($request->type)){
	    		$validator = Validator::make($request->all(), [
			        'email'=>'email',
			        'phone'=>'regex:/^([0-9\s\-\+\(\)]*)$/',
			        'password'=>'required',
			        'device_type'=>'required',
		            'device_token'=>'required'
				]);

			if(!$validator->fails()){
				if(!($request->email || $request->phone)){
	        		return "Email or phone is required.";
				}
				if($request->email){
	        		$user = User::where(['email'=>$request->email])->first();
				}
				if($request->phone){
	        		$user = User::where(['phone'=>$request->phone])->first();
				}
	        	if($user)
	        	{  
		            if(!Hash::check($request->password,$user->password))
		            {  
		                return response()->json([
							'status' => false,
							'message' => "Please enter correct email/mobile no. and password.",
						]);
		            }else{ 
		                if($user->type=="user"){
		                    if(strtolower($user->status)=="active"){
		                    	$date = Carbon::now('Asia/Kolkata');
            					$formatedDate = $date->format('Y-m-d H:i:s');
		                        $user->device_type = $request->device_type;
		                        $user->device_token = $request->device_token;
		                        $user->last_login_at = $formatedDate;
		                        $user->is_user_login = "yes";
		                        $token = $user->createToken('ARCC')->accessToken;
		                        $user->update();
		                        Auth::login($user);
		                        return response()->json([
									'status' => true,
									'access_token' => $token,
									'message' => "Login successfully",
									'data' =>$user
								]);
		                    }else{
		                        return response()->json([
									'status' => true,
									'message' => "Your account is blocked by admin. Please contact to admin."
								]);
		                    }
		                }else{
		                    return response()->json([
									'status' => true,
									'message' => "You are not having access. Please contact to admin."
								]);
		                }
		            }

		        }else{      
		            return response()->json([
							'status' => false,
							'message' => "Your account does not exist.",
						]);
		        }
	        }else{
	        		return response()->json([
						'status' => false,
						'message' => $validator->messages()->first(),
					]);
	    		}       

    	}else{

    			if($request->type){

    				try {
    						$useremail = $request->email;
    						$name = $request->name;
    						if($request->type == "gmail"){
    							$google_id = $request->social_id;
				            	$finduser = User::where('google_id',$google_id)->first();
    						}
    						if($request->type == "facebook"){
    							$facebook_id = $request->social_id;
				            	$finduser = User::where('facebook_id',$facebook_id)->first();
    						}
    						if($request->type == "apple"){
    							$apple_id = $request->social_id;
				            	$finduser = User::where('apple_id',$apple_id)->first();
    						}
    						
				            if($finduser){
				            	$token = $finduser->createToken('ARCC')->accessToken;
		                    	$finduser->update();
				                Auth::login($finduser);
				                return response()->json([
									'status' => true,
									'message' => "Login successfully.",
									'access_token' => $token,
									'data' => $finduser
								]);
				            }else{

				            	$user = User::where('email', $useremail)->first();

				            	if($user) {
					            	$user->device_type = $request->device_type;
					            	$user->device_token = $request->device_token;
					          
					            	if($request->type == "gmail"){
					            		$user->google_id = $request->social_id;
		    						}
		    						if($request->type == "facebook"){
					            		$user->facebook_id = $request->social_id;
		    						}
		    						if($request->type == "apple"){
					            		$user->apple_id = $request->social_id;
		    						}
		    						$user->is_user_login = "yes";
		    						$token = $user->createToken('ARCC')->accessToken;
					            	$user->update();
					            	Auth::login($user);
				                	return response()->json([
										'status' => true,
										'message' => "Login successfully.",
										'access_token' => $token,
										'data' => $user
									]);
					            }
					            else{
					            		
			            			$newInsertArr = [
					                    'email' => $useremail,
					                    'name' => $name,
					                    'device_token'=> $request->device_token,
					                    'device_type'=> $request->device_type,
					                    'user_verified'=> 1,
					                    'is_user_login'=> "yes"
			                		];
				                    if($request->type == "gmail"){
				                    	$newInsertArr['google_id'] = $request->social_id;	
		    						}
		    						if($request->type == "facebook"){
				                		$newInsertArr['facebook_id'] = $request->social_id;
					       
		    						}
		    						if($request->type == "apple"){
					            		$newInsertArr['apple_id'] = $request->social_id;
		    						}

		    						$newUser = User::create($newInsertArr);
		    						$token = $newUser->createToken('ARCC')->accessToken;
					            	$newUser->update();	

					                Auth::login($newUser);
					                return response()->json([
										'status' => true,
										'message' => "Login successfully.",
										'access_token' => $token,
										'data' => $newUser
									]);
				            	}
				                
				            }
    
				        } catch (Exception $e) {
				            dd($e->getMessage());
				        } 

    			}

    	}
        
    }

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
	        'email'=>'required|email'
		]);

        if(!$validator->fails()){
        	$result=DB::table('users')  
			        	->where(['email'=>$request->email])
			        	->first(); 
        	if(isset($result)){
        		if(($result->type == 'user')){
        			$token = $result->remember_token;
        			$name = $result->name;
        			$email = $result->email;
        			$verification_url = url('/reset_password',$token);
        			$emailData['name']  = $name;
        			$emailData['email'] = $email;
        			$emailData['token'] = $token;
        			$emailData['link'] = $verification_url;
        			sendEmail(['email'=>$emailData['email'],'name'=>$emailData['name']],'userForgotPasswordMail',$emailData);
        			return response()->json([
        				'status' => true,
        				'message' => "Reset password link has been sent on your registered email."
        			]);
        		}else{
        			return response()->json([
        				'status' => false,
        				'message' => "You have no access to change password from here.",
        			]);
        		}

        	}else{
        		return response()->json([
        			'status' => false,
        			'message' => "Account does not exists.",
        		]);
        	}
        	
        }else{
        		return response()->json([
					'status' => false,
					'message' => $validator->messages()->first(),
				]);
    		}  
    }

    public function reset_password($id){
        $result=DB::table('users')  
			        ->where(['remember_token'=>$id])
			        ->get();
        if(isset($result[0])){     	
            return view('front.auth.resetPasswordForm',compact('id'));
        }else{
        	$verified = '';
            return view('api.emailVerified',compact('verified'));
        }
    }

    public function reset_password_process(Request $request,$id)
    {   	
           $validatedData = $request->validate([
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password'           
            ]);
           $result = DB::table('users')  
                       ->where(['remember_token'=>$id])
                       ->get(); 
           if(isset($result[0])){
                if ($request->password === $request->confirm_password) {
                	$verified = 'yes';
                	$token = Str::random(30);
                    DB::table('users')  
	                    ->where(['id'=>$result[0]->id])
	                    ->update(['password'=>Hash::make($request->password),'remember_token'=>$token]);
                    return view('api.passwordChange',compact('verified'));
                }else{
                    return redirect()->back();
                }
        }else{
            return response()->json([
							'status' => false,
							'message' => "Please select right link for password update.",
						]);
        }
    }

    public function getData($id){
        $result = DB::table('users')  
			        ->where(['id'=>$id])
			        ->first();
        if(isset($result)){
            return response()->json([
					'status' => true,
					'message' => "Email authenticated.",
					'id' => $result
				]);
        }else{
            return response()->json([
					'status' => false,
					'message' => "Link expired.",
				]);
        }

    }

    public function emailVerification($id){
        $result = DB::table('users')  
        ->where(['remember_token'=>$id])
        ->first();
        $verified = '';
        if(isset($result)){
        	$verified = 'yes';
        	$data = User::find($result->id);
        	$data->email_verified_at = date("Y-m-d H:i:s");
        	$data->remember_token = Str::random(30);
        	$data->update();
       		return view('api.emailVerified',compact('verified'));

        }else{
       		return view('api.emailVerified',compact('verified'));
        }

    }

    public function otpVerification(Request $request,$id){
    	$validator = Validator::make($request->all(), [
	        'otp'=>'required'
		]);
		if(!$validator->fails()){
			$result = User::where('otp',$request->otp)
							->where('id',$id)
							->first();
			if(isset($result)){
				$otp_generated_at = $result->otp_generated_at;
                $otp_generated_at_sec = strtotime($otp_generated_at);
                $now = time(); // or your date as well
                $datediff = $now - $otp_generated_at_sec;
                $otp_time = round($datediff / (60));
                if($otp_time>5){
					$result->is_otp_expired = '1';
					$result->update();
                	return response()->json([
						'status' => false,
						'message' => "OTP expired.",
					]); 
                }else{
                	if($result->is_otp_expired){
						return response()->json([
							'status' => false,
							'message' => "OTP expired.",
						]); 
					}else{
						$result->is_otp_expired = '1';
						$result->user_verified = '1';
						$result->update();
						return response()->json([
							'status' => true,
							'message' => "OTP verified successfully.",
						]); 
					}
                }				
				
			}else{
				return response()->json([
					'status' => false,
					'message' => "Please enter correct OTP",
				]); 
			}        
        }else{
        		return response()->json([
					'status' => false,
					'message' => $validator->errors()->all(),
				]);
    		}      
    }

	public function logout()
    {   
       Auth::logout();
       session()->flush();
       return response()->json([
					'status' => true,
					'message' => "User is logout",
				]);     
	}

	public function resendOtp($id)
    {    
        $otp = 1111;  
        $user = User::where(['id'=>$id])->first();
        $user->otp = $otp;
	    $user->otp_generated_at = date("Y-m-d H:i:s");
		$user->is_otp_expired = '0';
        $user->update(); 
       return response()->json([
					'status' => true,
					'message' => "OTP sent successfully.",
				]);     
	}

}
	