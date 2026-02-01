<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .reference-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .reference-number {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
            font-family: 'Courier New', monospace;
        }
        .info-section {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Support Ticket Created</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $ticket->customer_name }},</p>
            
            <p>Thank you for contacting our support team. We have received your ticket and our team will review it shortly.</p>
            
            <div class="reference-box">
                <strong>Your Reference Number:</strong><br>
                <span class="reference-number">{{ $ticket->reference_number }}</span>
            </div>
            
            <p><strong>Important:</strong> Please save this reference number. You'll need it to check the status of your ticket.</p>
            
            <div class="info-section">
                <h3 style="margin-top: 0;">Ticket Details</h3>
                <p><strong>Problem Description:</strong><br>
                {{ $ticket->description }}</p>
                <p><strong>Submitted:</strong> {{ $ticket->created_at->format('M d, Y h:i A') }}</p>
            </div>
            
            <p>You can check the status of your ticket at any time using the reference number above.</p>
            
            <center>
                <a href="{{ url('/tickets/check') }}" class="button">Check Ticket Status</a>
            </center>
            
            <p>Our support team typically responds within 24 hours. You will receive an email notification when we reply to your ticket.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} AgentSupportHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
