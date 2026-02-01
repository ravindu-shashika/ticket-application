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
        .reply-box {
            background: #f0f7ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .reply-box h3 {
            margin-top: 0;
            color: #3b82f6;
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
            <h1>💬 New Reply to Your Ticket</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $ticket->customer->name }},</p>
            
            <p>Our support team has replied to your ticket.</p>
            
            <div class="reference-box">
                <strong>Ticket Reference:</strong> {{ $ticket->reference_number }}
            </div>
            
            <div class="reply-box">
                <h3>Support Agent Reply:</h3>
                <p>{{ $reply->message }}</p>
                <p style="font-size: 12px; color: #666; margin-top: 15px;">
                    Replied by {{ $reply->agent->name }} on {{ $reply->created_at->format('M d, Y h:i A') }}
                </p>
            </div>
            
            <p>You can view the full conversation and check your ticket status using the button below:</p>
            
            <center>
                <a href="{{ url('/tickets/check') }}" class="button">View Ticket Details</a>
            </center>
            
            <p>If you have additional questions or need further assistance, please don't hesitate to reach out.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>To respond, please visit our support portal using your reference number: {{ $ticket->reference_number }}</p>
            <p>&copy; {{ date('Y') }} AgentSupportHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
