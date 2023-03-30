@component('mail::message')
Hello {{ $user->name }},

Please login with the following authorization code:

{{ $code }}

The code will expire in {{ config('auth.tfa.timeout') /60 }} minute(s).

<i>Please ignore this email if you did not request this login authorization.</i>

Best regards,<br/>
Sims Database
@endcomponent