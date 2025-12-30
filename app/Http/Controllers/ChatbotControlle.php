<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotControlle extends Controller
{
    public function chat(Request $request)
    {
        // 1. CEK API KEY
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json(['reply' => 'FATAL: API Key belum terbaca. Coba jalankan "php artisan config:clear" di terminal.'], 500);
        }

        try {
            $userMessage = $request->input('message');
            $imageFile = $request->file('image');

            // 2. SIAPKAN DATA & PROMPT
            $contents = [];
            $systemPrompt = "Kamu adalah Dr. Sawit, pakar agronomi senior. Jawablah pertanyaan petani sawit dengan ramah, singkat, dan solutif. Jika ada foto, analisis visualnya.";

            if ($imageFile) {
                // Proses Gambar
                $imageData = base64_encode(file_get_contents($imageFile->getRealPath()));
                $mimeType = $imageFile->getMimeType();

                $contents[] = [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\n Pertanyaan Petani: " . ($userMessage ?? 'Tolong cek foto ini')],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $imageData
                            ]
                        ]
                    ]
                ];
            } else {
                // Proses Teks Saja
                $contents[] = [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\n Pertanyaan Petani: " . $userMessage]
                    ]
                ];
            }

            // 3. SETTING MODEL (KITA PAKAI VERSI STANDAR YANG PASTI ADA)
            // Pilihan Model:
            // "gemini-1.5-flash" -> Cepat, Pintar, Bisa Gambar (RECOMMENDED)
            // "gemini-1.5-pro"   -> Lebih Pintar, Lebih Lambat, Bisa Gambar
            $model = "gemini-3-flash-preview"; 

            // 4. KIRIM KE GOOGLE (BYPASS SSL)
            $response = Http::withoutVerifying()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => $contents
                ]);

            // 5. CEK HASIL
            if ($response->failed()) {
                // Tampilkan pesan error mentah dari Google biar gampang debug
                return response()->json(['reply' => "GOOGLE REJECT: " . $response->body()], 500);
            }

            $result = $response->json();
            $botReply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak bisa menganalisis itu.';
            
            // Format Bold Markdown (**) jadi HTML (<b>)
            $botReply = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $botReply);
            $botReply = nl2br($botReply);

            return response()->json(['reply' => $botReply]);

        } catch (\Exception $e) {
            return response()->json(['reply' => "SYSTEM ERROR: " . $e->getMessage()], 500);
        }
    }
}