<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            margin: 0;
        }
        
        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Header with gradient */
        .email-header {
            background: linear-gradient(135deg, #0A5C4B 0%, #1e7b66 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .header-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            position: relative;
            z-index: 1;
        }
        
        .header-icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }
        
        .email-header h1 {
            color: #ffffff;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }
        
        .email-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            position: relative;
            z-index: 1;
        }
        
        .header-badge {
            background: rgba(255, 215, 0, 0.2);
            color: #FFD700;
            padding: 8px 20px;
            border-radius: 50px;
            display: inline-block;
            margin-top: 15px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            border: 1px solid rgba(255, 215, 0, 0.3);
            position: relative;
            z-index: 1;
        }
        
        /* Content area */
        .email-content {
            padding: 40px 35px;
            background: #ffffff;
        }
        
        .greeting {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #e0e0e0;
        }
        
        .greeting h3 {
            color: #333;
            font-size: 22px;
            margin-bottom: 10px;
        }
        
        .greeting p {
            color: #666;
            font-size: 16px;
        }
        
        .highlight {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            border-left: 5px solid #0A5C4B;
        }
        
        /* Info cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .info-card:hover {
            transform: translateY(-3px);
            border-color: #0A5C4B;
            box-shadow: 0 10px 30px rgba(10, 92, 75, 0.1);
        }
        
        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0A5C4B;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .info-value {
            font-size: 18px;
            color: #333;
            font-weight: 600;
            word-break: break-word;
        }
        
        .info-value.small {
            font-size: 16px;
            font-weight: normal;
            color: #555;
        }
        
        /* Message box */
        .message-box {
            background: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .message-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .message-icon {
            width: 40px;
            height: 40px;
            background: #0A5C4B;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .message-icon svg {
            width: 20px;
            height: 20px;
            fill: white;
        }
        
        .message-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .message-content {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #0A5C4B;
            font-style: italic;
        }
        
        /* Action buttons */
        .action-buttons {
            text-align: center;
            margin: 35px 0 25px;
        }
        
        .btn {
            display: inline-block;
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            margin: 0 10px 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0A5C4B 0%, #1e7b66 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(10, 92, 75, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #0A5C4B;
            border: 2px solid #0A5C4B;
        }
        
        .btn-secondary:hover {
            background: #f0f7f5;
            transform: translateY(-2px);
        }
        
        /* Metadata */
        .metadata {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin: 25px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .meta-icon {
            width: 35px;
            height: 35px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .meta-icon svg {
            width: 18px;
            height: 18px;
            fill: #0A5C4B;
        }
        
        .meta-text {
            font-size: 14px;
            color: #666;
        }
        
        .meta-text strong {
            color: #333;
            display: block;
            font-size: 16px;
        }
        
        /* Footer */
        .email-footer {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            padding: 30px 35px;
            text-align: center;
            border-top: 1px solid rgba(10, 92, 75, 0.1);
        }
        
        .footer-logo {
            margin-bottom: 20px;
        }
        
        .footer-logo span {
            font-size: 24px;
            font-weight: 700;
            color: #0A5C4B;
        }
        
        .footer-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-link {
            display: inline-block;
            width: 36px;
            height: 36px;
            background: white;
            border-radius: 50%;
            margin: 0 5px;
            line-height: 36px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background: #0A5C4B;
            transform: translateY(-3px);
        }
        
        .social-link svg {
            width: 18px;
            height: 18px;
            fill: #0A5C4B;
            vertical-align: middle;
        }
        
        .social-link:hover svg {
            fill: white;
        }
        
        .copyright {
            font-size: 13px;
            color: #999;
            margin-top: 20px;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-content {
                padding: 25px 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .btn {
                display: block;
                width: 100%;
                margin: 10px 0;
            }
            
            .metadata {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
        /* Utility classes */
        .text-center {
            text-align: center;
        }
        
        .mt-4 {
            margin-top: 25px;
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #0A5C4B, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="header-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
            </div>
            <h1>New Message Received!</h1>
            <p>Someone just reached out through the contact form</p>
            <div class="header-badge">
                {{ strtoupper(now()->format('l, F j, Y')) }}
            </div>
        </div>
        
        <div class="email-content">
            <div class="greeting">
                <h3>👋 Hello Wiserlance Team!</h3>
                <p>You've received a new contact form submission with the following details:</p>
            </div>
            
            <div class="highlight">
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <div style="background: #0A5C4B; color: white; padding: 8px 20px; border-radius: 50px; font-size: 14px; font-weight: 600;">
                        PRIORITY: NORMAL
                    </div>
                    <div style="color: #666; font-size: 14px;">
                        📬 Response expected within 24 hours
                    </div>
                </div>
            </div>
            
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $data['fullname'] }}</div>
                </div>
                
                <div class="info-card">
                    <div class="info-label">Email Address</div>
                    <div class="info-value small">{{ $data['email'] }}</div>
                </div>
                
                @if(!empty($data['company']))
                <div class="info-card">
                    <div class="info-label">Company</div>
                    <div class="info-value">{{ $data['company'] }}</div>
                </div>
                @endif
                
                <div class="info-card">
                    <div class="info-label">Subject</div>
                    <div class="info-value">{{ $data['subject'] }}</div>
                </div>
            </div>
            
            <div class="message-box">
                <div class="message-header">
                    <div class="message-title">Message Content</div>
                </div>
                <div class="message-content">
                    "{{ $data['message'] }}"
                </div>
            </div>
            
            
            <div style="background: #e8f4f1; padding: 20px; border-radius: 12px; margin-top: 25px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#0A5C4B">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    <span style="font-weight: 600; color: #0A5C4B;">Pro Tip</span>
                </div>
                <p style="color: #333; margin: 0; font-size: 14px;">
                    For the best response rate, try to reply within the next 2-3 hours. 
                    This contact is in the <strong>{{ !empty($data['company']) ? $data['company'] : 'General' }}</strong> category.
                </p>
            </div>
        </div>
        
        <div class="email-footer">
            <div class="footer-logo">
                <span>WISERLANCE</span>
            </div>
            
            <div class="divider"></div>
            
            <div class="copyright">
                © {{ date('Y') }} Wiserlance. All rights reserved.
                <br>
                <span style="color: #999; font-size: 12px;">
                    D24 NRA0 | Dublin, Ireland
                </span>
                <br>
                <span style="color: #999; font-size: 11px; margin-top: 10px; display: block;">
                    This email was sent from the Wiserlance contact form.
                    If you weren't expecting this, please ignore or contact support.
                </span>
            </div>
        </div>
    </div>
</body>
</html>