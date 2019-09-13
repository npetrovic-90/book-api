Hello {{$user->name}}
You changed your email, so we need to verify this new address. Please use link below:
{{ route('verify',$user->verification_token) }}