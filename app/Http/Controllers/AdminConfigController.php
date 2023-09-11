<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminConfigController extends Controller
{
    public function initialLoad(){
      $context = [
       'DBStatus' => $this->DBStatus(),
      ];
      return view('welcome',$context);
    }


    public function emailVerified(){
        return view('verified_email');
      }



    public function DBStatus(){
        try {
           $response =  DB::connection()->getPdo();
           return 'Successful';
        } catch (\Exception $e) {
            return "Could not connect to the database." . $e ;
        }
    }

    public function clearCache(){
        $key = "";
        if($key == ''){
            \Artisan::call('config:clear');
        }  
    }

    public function migrate(){
        $key = "";
        if($key == ''){
            \Artisan::call('migrate');
        }  
    }

    public function migrate_refresh(){
        $key = "";
        if($key == ''){
            \Artisan::call('migrate:refresh');
        }  
    }

    public function passport_install(){
        $key = "";
        if($key == ''){
            \Artisan::call('passport:install');
        }  
    }
}
