<!DOCTYPE html>
<html>

<head>
    <title>Payment Callback</title>
</head>

<body>
    <script>
        // Send status back to opener window
        if (window.opener) {
            window.opener.postMessage({
                    status: "{{ $status }}",
                    message: "{{ $message }}",
                    payment_intent: {!! json_encode($payment_intent ?? null) !!}
                },
                window.location.origin
            );
            window.close(); // 👈 closes the popup
        } else {
            document.write("{{ $message }}"); // fallback if opened in same tab
        }
    </script>
</body>

</html>
