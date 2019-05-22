@component('mail::message')
Dear {{ $htmldata['name'] }},


{{ $htmldata['message'] }}
Email:  <strong> {{ $htmldata['email'] }} </strong><br>
Password: <strong>{{ $htmldata['password'] }}</strong><br>
Please click <a href= "{{ $htmldata['login_link'] }}">here</a> to login.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
