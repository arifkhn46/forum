@component('mail::message')
# one last step

We just need you to confirm your email address to prove that you are a human. You get it right? Cool.


@component('mail::button', ['url' => '#'])
 Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
