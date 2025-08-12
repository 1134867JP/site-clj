<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'message'    => 'required|string|max:3000',
            'email'      => 'nullable|email',
            'page_url'   => 'nullable|string|max:2048',
            'page_title' => 'nullable|string|max:255',
        ]);

        Feedback::create([
            'user_id'    => optional($request->user())->id,
            'email'      => $data['email'] ?? null,
            'page_url'   => $data['page_url'] ?? $request->headers->get('referer'),
            'page_title' => $data['page_title'] ?? null,
            'message'    => $data['message'],
            'meta'       => ['ip' => $request->ip(), 'agent' => $request->userAgent()],
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Obrigado pelo feedback!']);
        }

        return back()->with('success', 'Obrigado pelo feedback!');
    }
}
