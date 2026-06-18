<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmed</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .header { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); padding: 40px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 28px; font-weight: 700; letter-spacing: 1px; }
        .header p { margin: 10px 0 0; font-size: 16px; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .greeting { font-size: 22px; color: #1f2937; margin-top: 0; margin-bottom: 20px; }
        .details-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 25px; margin-bottom: 30px; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px; }
        .detail-row:last-child { margin-bottom: 0; border-bottom: none; padding-bottom: 0; }
        .detail-label { color: #64748b; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        .detail-value { color: #0f172a; font-weight: 700; font-size: 16px; text-align: right; }
        .spot-highlight { display: inline-block; background-color: #4f46e5; color: white; padding: 4px 12px; border-radius: 20px; font-size: 14px; }
        .action-button { display: inline-block; background-color: #4f46e5; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: 600; font-size: 16px; transition: background-color 0.3s; }
        .action-button:hover { background-color: #4338ca; }
        .footer { background-color: #1e293b; padding: 25px; text-align: center; color: #94a3b8; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reservation Confirmed!</h1>
            <p>Your parking spot has been successfully booked.</p>
        </div>
        <div class="content">
            <h2 class="greeting">Hello, {{ $reservation->user->name }}!</h2>
            <p style="color: #475569; line-height: 1.6; margin-bottom: 25px;">
                Thank you for using UniPark. We are pleased to confirm your parking reservation. Below are the details of your booking:
            </p>
            
            <div class="details-card">
                <div class="detail-row">
                    <span class="detail-label">Spot ID</span>
                    <span class="detail-value"><span class="spot-highlight">{{ $reservation->spot->zone->code }}-{{ $reservation->spot->spot_number }}</span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Zone</span>
                    <span class="detail-value">{{ $reservation->spot->zone->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Start Time</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($reservation->start_time)->format('M d, Y h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">End Time</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($reservation->end_time)->format('M d, Y h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Vehicle Plate</span>
                    <span class="detail-value">{{ $reservation->vehicle_plate }}</span>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 35px;">
                <a href="{{ route('reservations.show', $reservation->id) }}" class="action-button">View Reservation Details</a>
            </div>
        </div>
        <div class="footer">
            <p>Need help? Contact UniPark Support.</p>
            <p style="margin-top: 10px;">&copy; {{ date('Y') }} UniPark. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
