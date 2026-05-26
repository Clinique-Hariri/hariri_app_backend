<?php

namespace Modules\Products\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Modules\Products\Models\Reminder;
use Modules\Products\Models\Product;

class ReminderController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $reminders = Reminder::with('product')
            ->where('user_id', auth()->id())
            ->paginate(10);

        return $this->successResponse(
            data: $reminders
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $reminder = Reminder::firstOrCreate([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
        ]);

        return $this->successResponse(
            message: __('app.created_successfully', ['name' => 'Reminder']),
            data: $reminder
        );
    }

    public function destroy($id)
    {
        $reminder = Reminder::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $reminder->delete();

        return $this->successResponse(
            message: __('app.deleted_successfully', ['name' => 'Reminder'])
        );
    }
}