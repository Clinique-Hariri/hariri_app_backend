<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\DurationUnit;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Academy;
use App\Models\AcademySubscription;
use App\Models\Expense;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Enum\UserTypes;

class Analytics extends Controller
{
  public function index()
  {
    return view('dashboard.dashboard-analytics');
  }
}
