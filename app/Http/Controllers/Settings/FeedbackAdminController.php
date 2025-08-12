<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackAdminController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Feedback::class);

        $q = trim((string)$request->get('q'));
        $feedback = Feedback::query()
            ->when($q, function($query) use ($q) {
                $query->where('message', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('page_url', 'like', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('settings.feedback-index', compact('feedback','q'));
    }
}
