<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException as HttpException;

use App\Http\Controllers\Controller;

use App\User;

use App\Http\Requests\CreateTokenRequest;
use App\Http\Requests\DestroyTokenRequest;
use App\Http\Requests\RefreshTokenRequest;

class SessionController extends Controller
{
    /**
     * Instanciate a new OAuth2 JSON HTTP client
     */
    private function http()
    {
        return new HttpClient([
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
    }

    /**
     * Find or create password grant client
     */
    private function getClient()
    {
        $name = md5(env('APP_NAME', '-'));
        $client = Client::where('name', $name)->get()->first();

        if(!$client) {
            $client = (new ClientRepository)->createPasswordGrantClient(null, $name, url('/'));
        }

        return $client;
    }

    /**
     * /oauth/token client helper, avoids repeating ourselves...
     *
     * @param array $data
     * @return Response
     */
    private function getToken(array $data)
    {
        $response = null;

        $client = $this->getClient();

        try {

            $url = route('passport.token');

            // call /oauth/token endpoint with our data
            $response = $this->http()->post($url, [
                'form_params' => array_merge([
                        'client_id' => $client->id,
                        'client_secret' => $client->secret,
                        'scope' => ''
                    ], $data)
            ]);

        } catch(HttpException $e) {

            // if /oauth/token token grant fails, propagate response to caller
            $response = $e->getResponse();

        } finally {

            // in all cases, retrieve response body...
            $body = (string) $response->getBody();

            $data = json_decode($body, true);
            $status = $response->getStatusCode();

            // ... and build final response object
            $response = response($data, $status);
        }

        return $response;
    }

    /**
     * Refresh token
     *
     * @param RefreshTokenRequest $request
     * @return Response
     */
    public function refreshToken(RefreshTokenRequest $request)
    {
        $input = (object) $request->validated();

        return $this->getToken([
            'grant_type' => 'refresh_token',
            'refresh_token' => $input->refresh_token,
        ]);
    }

    /**
     * Create token
     *
     * @param CreateTokenRequest $request
     * @return Response
     */
    public function createToken(CreateTokenRequest $request)
    {
        $input = (object) $request->validated();

        return $this->getToken([
            'grant_type' => 'password',
            'username' => $input->email,
            'password' => $input->password,
        ]);
    }

    /**
     * Revoke user's token and refresh token, this logs out current user for
     * this session.
     *
     * @param DestroyTokenRequest $request
     * @return Response
     */
    public function destroyToken(DestroyTokenRequest $request)
    {
        $token = $request->user()->token();

        DB::transaction(function() use($token) {
            DB::table('oauth_access_tokens')
                    ->where('id', $token->id)
                    ->delete();
            DB::table('oauth_refresh_tokens')
                    ->where('access_token_id', $token->id)
                    ->delete();
        });

        return response(null, 204);
    }

    /**
     * Return current user to caller
     *
     * @param Request $request
     * @return Response
     */
    public function getUser(Request $request)
    {
        return $request->user();
    }
}
