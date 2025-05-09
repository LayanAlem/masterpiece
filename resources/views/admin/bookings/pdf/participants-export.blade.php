<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Booking Participants - {{ $booking->booking_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 22px;
            font-weight: bold;
            margin: 0 0 5px;
            color: #793509;
        }

        h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 20px;
            color: #793509;
        }

        h3 {
            color: #793509;
            font-size: 16px;
            margin: 15px 0;
        }

        .booking-info {
            margin-bottom: 20px;
        }

        .booking-info p {
            margin: 5px 0;
        }

        .booking-info strong {
            font-weight: bold;
            color: #793509;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #793509;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .participant-name {
            font-weight: bold;
            color: #793509;
        }

        @page {
            margin: 40px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('assets/images/logo.png') }}" alt="Company Logo" class="logo">
        <h1>Booking Participants List</h1>
        <h2>Booking #{{ $booking->booking_number }}</h2>
    </div>

    <div class="booking-info">
        <p><strong>Booking Date:</strong> {{ $booking->created_at->format('M d, Y') }}</p>
        <p><strong>Customer:</strong> {{ $booking->user->first_name }} {{ $booking->user->last_name }}</p>
        <p><strong>Email:</strong> {{ $booking->user->email }}</p>
        <p><strong>Phone:</strong> {{ $booking->user->phone ?? 'N/A' }}</p>
        <p><strong>Activity:</strong> {{ $booking->activity->name ?? 'Multiple Activities' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
        <p><strong>Total Participants:</strong> {{ count($participants) }}</p>
    </div>

    <h3>Participants Details</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Age</th>
                <th>Special Requirements</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($participants as $index => $participant)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="participant-name">{{ $participant->name }}</td>
                    <td>{{ $participant->email ?? 'N/A' }}</td>
                    <td>{{ $participant->phone ?? 'N/A' }}</td>
                    <td>{{ $participant->age ?? 'N/A' }}</td>
                    <td>{{ $participant->special_requirements ?? 'None' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (isset($booking->activity) && $booking->activity)
        <h3>Activity Information</h3>
        <table>
            <tr>
                <th>Activity Name</th>
                <td>{{ $booking->activity->name }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $booking->activity->location }}</td>
            </tr>
            <tr>
                <th>Date & Time</th>
                <td>{{ $booking->scheduled_date ? $booking->scheduled_date->format('M d, Y h:i A') : 'To be confirmed' }}
                </td>
            </tr>
            <tr>
                <th>Duration</th>
                <td>{{ $booking->activity->duration ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Meeting Point</th>
                <td>{{ $booking->activity->meeting_point ?? 'To be confirmed' }}</td>
            </tr>
        </table>
    @endif

    <div class="footer">
        <p>This document was automatically generated on {{ now()->format('M d, Y h:i A') }}</p>
        <p>© {{ date('Y') }} Masterpiece Tourism. All rights reserved.</p>
    </div>
</body>

</html>
