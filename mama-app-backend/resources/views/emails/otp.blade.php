<x-mail::message>
# Password Reset OTP

Hello {{ $user->username ?? $user->email }},

Your OTP for password reset is:

# <b>{{ $otp }}</b>

This OTP will expire in 10 minutes. If you did not request a password reset, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
