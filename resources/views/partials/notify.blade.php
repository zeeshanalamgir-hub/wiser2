<link rel="stylesheet" href="{{ asset('assets/global/css/iziToast.min.css') }}">
<script src="{{ asset('assets/global/js/iziToast.min.js') }}"></script>
@if (session()->has('notify'))
    @php $notifications = session('notify'); @endphp

    @if (is_array($notifications))
        @foreach ($notifications as $key => $notification)
            @if (is_array($notification) && count($notification) == 2)
                <!-- Handling indexed array: [['success', 'Job has been created!']] -->
                <script>
                    "use strict";
                    var notificationType = "{{ $notification[0] }}"; // e.g., 'success'
                    var notificationMessage = "{{ $notification[1] }}"; // e.g., 'Job has been created successfully!'

                    if (typeof iziToast[notificationType] === 'function') {
                        iziToast[notificationType]({
                            message: notificationMessage,
                            position: "topRight"
                        });
                    } else {
                        console.error('Invalid notification type:', notificationType);
                    }
                </script>
            @elseif(is_string($key) && is_string($notification))
                <!-- Handling associative array: ['success' => 'Job has been created!'] -->
                <script>
                    "use strict";
                    var notificationType = "{{ $key }}"; // e.g., 'success'
                    var notificationMessage = "{{ $notification }}"; // e.g., 'Job has been created successfully!'

                    if (typeof iziToast[notificationType] === 'function') {
                        iziToast[notificationType]({
                            message: notificationMessage,
                            position: "topRight"
                        });
                    } else {
                        console.error('Invalid notification type:', notificationType);
                    }
                </script>
            @else
                <!-- If notification is not in the expected format, log an error -->
                <script>
                    console.error('Unexpected notification structure:', @json($notification));
                </script>
            @endif
        @endforeach
    @endif
@endif
@if ($errors->any())
    @php
        $collection = collect($errors->all());
        $errors = $collection->unique();
    @endphp

    <script>
        "use strict";
        @foreach ($errors as $error)
            iziToast.error({
                message: '{{ __($error) }}',
                timeout: 70000,
                position: "topRight"
            });
        @endforeach
    </script>
@endif
<script>
    "use strict";

    function notify(status, message) {
        // iziToast.success({
        //     message: message,
        //     timeout: 70000,
        //     position: "topRight"
        // });

        if (status === 'error') {
            iziToast.error({
                message: message,
                timeout: 70000,
                position: "topRight"
            });
        } else {
            iziToast.success({
                message: message,
                timeout: 70000,
                position: "topRight"
            });
        }
    }
</script>
