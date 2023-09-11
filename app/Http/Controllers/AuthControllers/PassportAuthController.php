<?php
namespace App\Http\Controllers\AuthControllers;
use App\Notifications\LoginNotification;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Validator;
use Auth;
use Carbon\Carbon;

class PassportAuthController extends Controller
{
    use ApiResponser;
    /**
     * Registration 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'=>'required',
            'last_name'=>'required',
            'residential_address'=>'nullable',
            'phone_number'=>'nullable|min:4|unique:users',
            'email_address'=>'required|email|unique:users',
            'password'=>'required|min:8',
        ]);

        if($validator->fails()){
             return  $this->errorResponse($validator->errors());
        }
  
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'residential_address' => $request->residential_address,
            'phone_number' => $request->phone_number,
            'email_address' => $request->email_address,
            'password' => bcrypt($request->password)
        ]);
   
        $token = $user->createToken('AccessTokensAuth')->accessToken;
  
        return $this->loginResponse($user,$token);
    }
   
  
    public function login(Request $request)
    {
        $email = $request->email_address;
        $data = [
            'email_address' => $email,
            'password' => $request->password
        ];
        //check if user of email exists
        $result_email = User::where('email_address',$email)->first();

        if($result_email == null){
            return $this->errorResponse('User with email address provided does not exist kindly register');
        }
        //Check if the user can login with the provided credentials
        $result_login = Auth::attempt($data);

        if ($result_login) {

            $user = Auth::user();
            //Check if the user has verified their email address
            if(strtoupper($user->isVerified) == 'FALSE'){

            }
            
            $result = Auth::login($user);  

            $token = $user->createToken('AccessTokensAuth')->accessToken;
            
            $user->employeeInfo;

            //Send welcome Email
            \Mail::to($email)->send(new \App\Mail\WelcomeEmail($user->first_name.' '.$user->last_name));

            return $this->loginResponse($user,$token);

        } else {
            $message = ['Unauthorized,Incorrect password, kindly check your password'];

            $code = Response::HTTP_UNAUTHORIZED;

            return $this->errorResponse($message,$code);
        }
    }
 
    public function userInfo() 
    {
     $user = Auth::user();
     $user->employeeInfo;
     $message = 'Successfully returned User profile';
     return $this->successResponse($user,$message);
    }

    public function verificationEmail($user){

    }

    public function verifyEmail($generated_number){

        $user_id = substr($generated_number,0,1);

        $user = User::find($user_id);

        if($user){
            //update the verification to true and recod the time
            $user->isVerified = 'TRUE';

            $user->email_verified_at = Carbon::now();

            $result = $user->save();

            if($result){
                return redirect()->route('email.verify');
            }
        }
        


    }

    public function users(){
      
        $users = User::orderBy('created_at', 'desc')->get();
        foreach($users as $user){
            $user->employeeInfo;
        }

        if($users){
            return $this->successResponse($users,'Successfully returned users');
        }else{
            return $this->errorResponse('No users exist,kindly get started and Register a new User ',404);
        }
    }
    

     
    public function test() 
    {
     $message = 'Successfully returned User profile';
     return response('API is Running just fine');
    }
}