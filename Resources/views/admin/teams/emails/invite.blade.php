You have been invited to join team {{$team->name}}.<br>
Click here to join: <a href="{{action('Auth\TeamInviteController@acceptInvite', $invite->accept_token)}}">{{action('Auth\TeamInviteController@acceptInvite', $invite->accept_token)}}</a>
