<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $model = $request->input('model');
        $message = $request->input('message');
        $session_id = $request->input('session_id');

        $reply = '';
        if ($model === 'gpt') {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json'
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [['role' => 'user', 'content' => $message]]
            ]);
            $reply = $response->json()['choices'][0]['message']['content'] ?? 'Không có phản hồi';
        } else {
            $response = Http::post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
                'contents' => [['role' => 'user', 'parts' => [['text' => $message]]]]
            ]);
            $reply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Không có phản hồi';
        }

        DB::table('chat_logs')->insert([
            'session_id' => $session_id,
            'message' => $message,
            'reply' => $reply,
            'created_at' => now()
        ]);

        return response()->json(['reply' => $reply]);
    }

    public function history(Request $request)
    {
        $session_id = $request->query('session_id');
        $logs = DB::table('chat_logs')->where('session_id', $session_id)->orderBy('id')->get();
        return response()->json($logs);
    }

    public function downloadHistory(Request $request)
    {
        $session_id = $request->query('session_id');
        $logs = DB::table('chat_logs')->where('session_id', $session_id)->orderBy('id')->get();
        return response()->streamDownload(function () use ($logs) {
            echo json_encode($logs, JSON_PRETTY_PRINT);
        }, 'chat_history.json');
    }

    public function deleteHistory(Request $request)
    {
        $session_id = $request->query('session_id');
        DB::table('chat_logs')->where('session_id', $session_id)->delete();
        return response()->json(['message' => 'Đã xóa toàn bộ lịch sử chat']);
    }
}
