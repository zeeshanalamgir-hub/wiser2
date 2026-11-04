<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Connect Embedded Components</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://connect-js.stripe.com/v1.0/connect.js"></script>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f6f9fc;
        }

        h1 {
            color: #32325d;
            margin-bottom: 30px;
        }

        .component-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .component-container h2 {
            color: #32325d;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
        }

        #error {
            background-color: #fff0f0;
            color: #c23d4b;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ffcdd2;
            margin-bottom: 20px;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tab-button {
            padding: 10px 20px;
            border: 1px solid #cbd5e0;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .tab-button:hover {
            background: #f7fafc;
        }

        .tab-button.active {
            background: #635bff;
            color: white;
            border-color: #635bff;
        }

        .component-wrapper {
            display: none;
        }

        .component-wrapper.active {
            display: block;
        }
    </style>
</head>

<body>
    <h1>Stripe Connect Embedded Components</h1>

    <div id="error" hidden>
        <strong>Error:</strong> <span id="error-message"></span>
    </div>

    <div class="tabs">
        <button class="tab-button active" data-component="account-onboarding">Account Onboarding</button>
    </div>

    <div class="component-wrapper active" id="account-onboarding-wrapper">
        <div class="component-container">
            <h2>Account Onboarding</h2>
            <div id="account-onboarding-container"></div>
        </div>
    </div>





    {{--  <script>
        // Store component instances
        const components = {};
        let currentComponent = 'account-onboarding';
        let stripeConnectInstance = null;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Function to fetch account session client secret from your backend
        async function fetchClientSecret() {
            try {
                // Replace this URL with your actual backend endpoint
                const response = await fetch('/account_session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    // You might need to send the connected account ID
                    // body: JSON.stringify({ account: 'acct_xxxxx' })
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch client secret');
                }

                const data = await response.json();
                return data.client_secret;
            } catch (error) {
                console.error('Error fetching client secret:', error);
                showError('Failed to fetch client secret. Make sure your backend is set up correctly.');

                // For demo purposes, you can return a hardcoded secret (NOT for production!)
                // return 'accs_secret__T93w902Ybmjox8ctVlHIJ23eXPB4E4e7EXnC3swLWVRzuiW';
                throw error;
            }
        }

        // Function to show error messages
        function showError(message) {
            const errorDiv = document.getElementById('error');
            const errorMessage = document.getElementById('error-message');
            errorMessage.textContent = message;
            errorDiv.hidden = false;
        }

        // Function to hide error messages
        function hideError() {
            document.getElementById('error').hidden = true;
        }

        // Initialize Connect instance
        async function initializeConnect() {
            try {
                // Check if StripeConnect is loaded
                if (typeof window.StripeConnect === 'undefined') {
                    throw new Error('Stripe Connect library not loaded. Check your internet connection.');
                }

                console.log('Initializing Stripe Connect...');

                // Use loadConnectAndInitialize from Stripe Connect JS
                const stripeConnectInstance = await StripeConnect.loadConnectAndInitialize({

                    publishableKey: 'pk_test_51MP68aCWTG6yySfZu24ZEWKqmDwN0fDK9i2yGujMi2w9FtK1sY01AiTeb0PYpMiRFUlsjriBlgHICd3ewikNRRoL00IDnCrigU',
                    fetchClientSecret: fetchClientSecret,
                    appearance: {
                        // Customize the appearance
                        variables: {
                            colorPrimary: '#635bff',
                            colorBackground: '#ffffff',
                            colorText: '#32325d',
                            colorDanger: '#df1b41',
                            fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, sans-serif',
                            spacingUnit: '4px',
                            borderRadius: '8px',
                        },
                    },
                });

                console.log('Stripe Connect instance created:', stripeConnectInstance);

                // Create all components
                components.accountOnboarding = stripeConnectInstance.create('account-onboarding');
                components.accountManagement = stripeConnectInstance.create('account-management');
                components.payments = stripeConnectInstance.create('payments');
                components.payouts = stripeConnectInstance.create('payouts');
                components.notificationBanner = stripeConnectInstance.create('notification-banner');

                // Mount the default component
                mountComponent('account-onboarding');

                hideError();
            } catch (error) {
                console.error('Error initializing Connect:', error);
                showError('Failed to initialize Stripe Connect: ' + error.message);
            }
        }

        // Function to mount a specific component
        function mountComponent(componentName) {
            // Unmount current component if it exists
            if (components[toCamelCase(currentComponent)]) {
                components[toCamelCase(currentComponent)].unmount();
            }

            // Update UI
            document.querySelectorAll('.component-wrapper').forEach(wrapper => {
                wrapper.classList.remove('active');
            });
            document.getElementById(`${componentName}-wrapper`).classList.add('active');

            // Mount new component
            const container = document.getElementById(`${componentName}-container`);
            const component = components[toCamelCase(componentName)];

            if (component) {
                component.mount(container);
            }

            currentComponent = componentName;
        }

        // Convert kebab-case to camelCase
        function toCamelCase(str) {
            return str.replace(/-([a-z])/g, (g) => g[1].toUpperCase());
        }

        // Tab switching
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');

                const componentName = button.getAttribute('data-component');
                mountComponent(componentName);
            });
        });

        // Initialize on page load
        window.addEventListener('load', () => {
            // Wait a bit for the Stripe Connect script to fully load
            setTimeout(() => {
                initializeConnect();
            }, 500);
        });
    </script>  --}}
    <script>
        const accountOnboarding = stripeConnectInstance.create('account-onboarding');
        accountOnboarding.setOnExit(() => {
            console.log('User exited the onboarding flow');
        });
        container.appendChild(accountOnboarding);
    </script>
</body>

</html>
