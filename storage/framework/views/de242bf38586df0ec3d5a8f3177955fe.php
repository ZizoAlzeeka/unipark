<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reservation Alert</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .header { background: linear-gradient(135deg, #1f2937 0%, #0f172a 100%); padding: 30px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 1px; }
        .content { padding: 40px 30px; }
        .alert-box { background-color: #fffbeb; border-left: 4px solid #fbbf24; padding: 15px 20px; margin-bottom: 25px; border-radius: 4px; }
        .alert-box p { margin: 0; color: #92400e; font-weight: 500; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .details-table th, .details-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .details-table th { background-color: #f8fafc; color: #475569; font-weight: 600; width: 35%; }
        .details-table td { color: #1e293b; font-weight: 500; }
        .action-button { display: inline-block; background-color: #1f2937; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; font-size: 14px; transition: background-color 0.3s; margin-top: 30px; }
        .action-button:hover { background-color: #0f172a; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; color: #64748b; font-size: 13px; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Reservation Alert</h1>
        </div>
        <div class="content">
            <div class="alert-box">
                <p>A new parking reservation has been made on the system.</p>
            </div>
            
            <table class="details-table">
                <tr>
                    <th>User Name</th>
                    <td><?php echo e($reservation->user->name); ?></td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td><span style="text-transform: capitalize;"><?php echo e($reservation->user->role); ?></span></td>
                </tr>
                <tr>
                    <th>Spot ID</th>
                    <td><strong style="color: #4f46e5;"><?php echo e($reservation->spot->zone->code); ?>-<?php echo e($reservation->spot->spot_number); ?></strong></td>
                </tr>
                <tr>
                    <th>Start Time</th>
                    <td><?php echo e(\Carbon\Carbon::parse($reservation->start_time)->format('M d, Y h:i A')); ?></td>
                </tr>
                <tr>
                    <th>End Time</th>
                    <td><?php echo e(\Carbon\Carbon::parse($reservation->end_time)->format('M d, Y h:i A')); ?></td>
                </tr>
                <tr>
                    <th>Vehicle Plate</th>
                    <td><?php echo e($reservation->vehicle_plate); ?></td>
                </tr>
            </table>
            
            <div style="text-align: center;">
                <a href="<?php echo e(route('admin.reservations.show', $reservation->id)); ?>" class="action-button">View in Admin Dashboard</a>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated notification from the UniPark System.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH H:\MAMP\htdocs\unipark\resources\views/emails/reservation-admin.blade.php ENDPATH**/ ?>