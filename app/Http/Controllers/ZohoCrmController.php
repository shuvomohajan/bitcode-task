<?php

namespace App\Http\Controllers;

use Http;
use Illuminate\Http\Request;

class ZohoCrmController extends Controller
{
    public function oauthZohoCrmAuthorize(Request $request)
    {
        session()->put('zoho_client_id', $request->input('client_id'));
        session()->put('zoho_client_secret', $request->input('client_secret'));
        $response = Http::post("https://accounts.zoho.com/oauth/v2/auth?scope=ZohoCRM.modules.leads.ALL&client_id=" . $request->input('client_id') . "&response_type=code&access_type=offline&redirect_uri=" . route('oauth.zoho.crm.callback'));
        return redirect($response->effectiveUri());
    }

    public function oauthZohoCrmCallback()
    {
        if (isset(request()->code)) {
            $formData = [
                "grant_type"    => "authorization_code",
                "client_id"     => session('zoho_client_id'),
                "client_secret" => session('zoho_client_secret'),
                "redirect_uri"  => route('oauth.zoho.crm.callback'),
                "code"          => request()->code,
            ];
            $response = Http::asForm()->post("https://accounts.zoho.com/oauth/v2/token", $formData);
            $token = $response->body();
            \Session::put('zoho_auth', $token);
        }
        return redirect()->route('task.1');
    }

    public function zohoCrmStore(Request $request)
    {
        dd($request->toArray());
    }
}
