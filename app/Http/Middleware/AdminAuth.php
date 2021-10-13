<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;


class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(Auth::user()){
            $admin = Auth::user();
            $id = $admin->id;
            $admin = User::find($id);
            if($admin->type=="super_admin" || $admin->type=="admin"){

                if($admin->status=="Active"){

                  }else{
                    Auth::logout();
                    session()->flush();
                    return redirect('/admin');
                  }

              }else{
                    Auth::logout();
                    session()->flush();
                    return redirect('/admin');
              }

        }else{

            $request->session()->flash('Access_Denied',"You Are Not Authentic To Acces This Page");
            return redirect('admin');
        }

        return $next($request);
    }
}

