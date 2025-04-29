<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display the detailed view of an activity.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Fetch the activity with necessary relations
        $activity = Activity::with(['categoryType.mainCategory', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

        // Calculate duration in hours
        $startDate = \Carbon\Carbon::parse($activity->start_date);
        $endDate = \Carbon\Carbon::parse($activity->end_date);
        $durationHours = $startDate->diffInHours($endDate);

        // Format duration
        if ($durationHours < 24) {
            $activity->formatted_duration = $durationHours . ' hours';
        } else {
            $days = floor($durationHours / 24);
            $remainingHours = $durationHours % 24;
            $activity->formatted_duration = $days . ' day' . ($days > 1 ? 's' : '') .
                ($remainingHours > 0 ? ' ' . $remainingHours . ' hour' . ($remainingHours > 1 ? 's' : '') : '');
        }

        // Get similar activities (same category type, excluding current)
        $similarActivities = Activity::where('category_type_id', $activity->category_type_id)
            ->where('id', '!=', $activity->id)
            ->take(3)
            ->get();

        // Get weather data for the activity location
        // This is a placeholder - in a real app, you might use an API
        $weather = (object)[
            'current_temp' => rand(20, 35),
            'condition' => ['Clear skies', 'Partly cloudy', 'Sunny'][rand(0, 2)],
            'humidity' => rand(30, 70),
            'uv_index' => ['Low', 'Moderate', 'High', 'Very High'][rand(0, 3)],
            'forecast' => [
                [
                    'time' => '9 AM',
                    'icon' => 'fa-sun',
                    'temp' => rand(20, 28)
                ],
                [
                    'time' => '12 PM',
                    'icon' => 'fa-sun',
                    'temp' => rand(27, 35)
                ],
                [
                    'time' => '3 PM',
                    'icon' => 'fa-sun',
                    'temp' => rand(25, 32)
                ],
                [
                    'time' => '6 PM',
                    'icon' => 'fa-moon',
                    'temp' => rand(20, 25)
                ]
            ]
        ];

        return view('public.pages.detailed', compact('activity', 'similarActivities', 'weather'));
    }
}
