<h3>You received a message from : {{ $contact_us->sender_name }}</h3>

<p>
Application id: {{ $contact_us->application_id }}
</p>
<p>
Application name: {{ $contact_us->application_name }}
</p>

@if ($contact_us->emergency_type !== null)
    <p>
    Emergency type : 
    <ul>
        @foreach ($contact_us->emergency_type as $eme)
        <li>{{ ucfirst(str_replace("_"," ",$eme)) }}</li>
        @endforeach
    </ul>
    </p>
@endif

<p>
Message : {{ $contact_us->message }}
</p>
<p>
Email : {{ $contact_us->email }}
</p>
<p>
Phone number : {{ $contact_us->phone_number }}
</p>