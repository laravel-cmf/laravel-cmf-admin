Click here to reset your password: <a href="{{ $link = cmf_url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
