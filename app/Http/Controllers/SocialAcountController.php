<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAcountController extends Controller
{
    public function redirectToProvider($provider){
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider){
        try {
            $user = Socialite::driver($provider)->user();
        }catch (\Exception $e){
            return redirect('/login');
        }
        $authUser = $this->findOrCreateUser($user,$provider);
        Auth::login($authUser,true);
        return redirect('/home');
    }

    public function findOrCreateUser($socialUser,$provider){
       $account = Socialite::where('provider_name',$provider)->where('provider_name',$socialUser->getId())->first();
       if($account){
           return $account->user;
       }else{
           $user = User::where('email','=',$socialUser->getEmail())->first();
           if(!$user){
               $user = User::create([
                   'email'=>$socialUser->getEmail(),
                   'name'=>$socialUser->getName()
               ]);
           }
           $user->accounts()->create([
               'provider_name'=>$provider,
               'provider_id'=>$socialUser->getId(),
           ]);
           return $user;
       }
    }
}
