<x-mail::message>
# Hello, {{ $firstname }}!

Thank you for registering with **{{ config('app.name') }}**.

Please use the 4-digit code below to verify your email address.
This code is valid for **15 minutes**.

<x-mail::panel>
# {{ $code }}
</x-mail::panel>

Enter this code on the verification page to complete your registration.

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
