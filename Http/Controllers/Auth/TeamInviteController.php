<?php

namespace Modules\Cockpit\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Cockpit\Services\Teamwork\Teamwork;

class TeamInviteController extends Controller
{
    public function acceptInvite($token)
    {
        $teamwork = app('teamwork');
        $invite = $teamwork->getInviteFromAcceptToken($token);
        if (!$invite) {
            abort(404);
        }

        if (auth()->check()) {
            $teamwork->acceptInvite($invite);
            return redirect()->route('teams.index');
        } else {
            session(['invite_token' => $token]);
            return redirect()->to('login');
        }
    }
}
