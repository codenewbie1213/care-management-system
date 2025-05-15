<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        // Return system-wide compliance metrics, trends, departmental comparison, etc.
    }

    public function manager(Request $request)
    {
        // Return department compliance, team performance, upcoming audits, etc.
    }

    public function staff(Request $request)
    {
        // Return assigned audits, action plans, personal progress, audit history, etc.
    }
} 