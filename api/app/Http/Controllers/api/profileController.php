<?php 
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\api\BaseController as Base;
use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;
use Token;


class profileController extends base
{
	public function index()
	{
		$user = User::all()->where('id', auth()->user()->id);
		return $this->sendResponse($user->toArray(), ' gggggg');
	}

	public function logout(Request $request)
    {
        $value = $request->bearerToken();
        $id= (new Parser())->parse($value)->getHeader('jti');

        $token=  DB::table('oauth_access_tokens')
            ->where('id', $id)
            ->update(['revoked' => true]);

        $token = $request->user()->tokens->find($id);
        $token->revoke();
        $token->delete();

        $json = [
            'success' => true,
            'code' => 200,
            'message' => 'You are Logged out.',
        ];
        return response()->json($json, '200');
    }

}