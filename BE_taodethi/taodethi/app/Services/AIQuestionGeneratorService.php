<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIQuestionGeneratorService
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        $this->apiUrl = config('services.openai.api_url', 'https://api.openai.com/v1/chat/completions');
    }

    public function extractTextFromFile($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $content = '';

        try {
            switch ($extension) {
                case 'pdf':
                    $content = $this->extractTextFromPDF($file);
                    break;
                case 'docx':
                case 'doc':
                    $content = $this->extractTextFromDOCX($file);
                    break;
                case 'pptx':
                case 'ppt':
                    $content = $this->extractTextFromPPTX($file);
                    break;
                case 'txt':
                    $content = file_get_contents($file->getRealPath());
                    break;
                default:
                    throw new \Exception("Định dạng file không được hỗ trợ: {$extension}");
            }
        } catch (\Exception $e) {
            Log::error('Error extracting text from file: ' . $e->getMessage());
            throw new \Exception("Không thể đọc file: " . $e->getMessage());
        }

        return $content;
    }

    private function extractTextFromPDF($file)
    {
        $command = "pdftotext " . escapeshellarg($file->getRealPath()) . " - 2>&1";
        $content = @shell_exec($command);
        
        if ($content === false || empty(trim($content)) || strpos($content, 'Error') !== false) {
            throw new \Exception("Không thể đọc PDF. Vui lòng cài đặt pdftotext (poppler-utils) hoặc chuyển đổi file sang TXT.");
        }
        
        return $content;
    }

    private function extractTextFromDOCX($file)
    {
        $zip = new \ZipArchive();
        if ($zip->open($file->getRealPath()) === TRUE) {
            $content = $zip->getFromName('word/document.xml');
            $zip->close();
            
            if ($content) {
                $content = strip_tags($content);
                $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
                return $content;
            }
        }
        
        throw new \Exception("Không thể đọc file DOCX");
    }

    private function extractTextFromPPTX($file)
    {
        $zip = new \ZipArchive();
        if ($zip->open($file->getRealPath()) === TRUE) {
            $content = '';
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (strpos($filename, 'ppt/slides/slide') !== false) {
                    $slideContent = $zip->getFromIndex($i);
                    if ($slideContent) {
                        $slideContent = strip_tags($slideContent);
                        $slideContent = html_entity_decode($slideContent, ENT_QUOTES, 'UTF-8');
                        $content .= $slideContent . "\n";
                    }
                }
            }
            $zip->close();
            return $content;
        }
        
        throw new \Exception("Không thể đọc file PPTX");
    }

    public function generateQuestions(
        string $content,
        int $count,
        string $type,
        string $difficulty = 'mix',
        bool $generateDistractors = true,
        bool $includeCitations = false
    ): array {
        if (empty($this->apiKey)) {
            return $this->generateQuestionsFallback($content, $count, $type, $difficulty, $generateDistractors, $includeCitations);
        }

        try {
            return $this->generateQuestionsWithAI($content, $count, $type, $difficulty, $generateDistractors, $includeCitations);
        } catch (\Exception $e) {
            Log::warning('AI API failed, using fallback: ' . $e->getMessage());
            return $this->generateQuestionsFallback($content, $count, $type, $difficulty, $generateDistractors, $includeCitations);
        }
    }

    private function generateQuestionsWithAI(
        string $content,
        int $count,
        string $type,
        string $difficulty,
        bool $generateDistractors,
        bool $includeCitations
    ): array {
        $typeMap = [
            'mcq' => 'multiple-choice',
            'tf' => 'true/false',
            'stem' => 'essay prompt'
        ];

        $difficultyMap = [
            'easy' => 'dễ',
            'medium' => 'trung bình',
            'hard' => 'khó',
            'mix' => 'trộn các mức độ'
        ];

        $prompt = "Bạn là một giáo viên chuyên nghiệp. Hãy tạo {$count} câu hỏi từ nội dung sau:\n\n";
        $prompt .= "Nội dung:\n{$content}\n\n";
        $prompt .= "Yêu cầu:\n";
        $prompt .= "- Loại câu hỏi: {$typeMap[$type]}\n";
        $prompt .= "- Độ khó: {$difficultyMap[$difficulty]}\n";
        
        if ($type === 'mcq' && $generateDistractors) {
            $prompt .= "- Tạo 4 lựa chọn (1 đúng, 3 nhiễu hợp lý)\n";
        }
        
        if ($includeCitations) {
            $prompt .= "- Ghi rõ đoạn nguồn cho mỗi câu hỏi\n";
        }

        $prompt .= "\nTrả về JSON với format:\n";
        $prompt .= '[{"stem": "Câu hỏi", "options": ["A", "B", "C", "D"], "correct": "A", "citation": "Trang X"}]';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(60)->post($this->apiUrl, [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Bạn là trợ lý tạo câu hỏi. Trả về JSON hợp lệ.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
        ]);

        if ($response->successful()) {
            $result = $response->json();
            $content = $result['choices'][0]['message']['content'] ?? '';
            
            $jsonMatch = preg_match('/\[.*\]/s', $content, $matches);
            if ($jsonMatch) {
                $questions = json_decode($matches[0], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($questions)) {
                    return array_map(function($q, $index) use ($type) {
                        return [
                            'id' => $index + 1,
                            'type' => $type,
                            'stem' => $q['stem'] ?? '',
                            'options' => $q['options'] ?? [],
                            'correct' => $q['correct'] ?? '',
                            'citation' => $q['citation'] ?? null,
                        ];
                    }, $questions, array_keys($questions));
                }
            }
        }

        throw new \Exception('Không thể tạo câu hỏi từ AI');
    }

    private function generateQuestionsFallback(
        string $content,
        int $count,
        string $type,
        string $difficulty,
        bool $generateDistractors,
        bool $includeCitations
    ): array {
        $questions = [];
        $sentences = $this->extractSentences($content);
        
        if (empty($sentences)) {
            throw new \Exception('Không thể trích xuất nội dung từ tài liệu');
        }

        for ($i = 0; $i < $count && $i < count($sentences); $i++) {
            $sentence = $sentences[$i] ?? $sentences[array_rand($sentences)];
            
            if ($type === 'mcq') {
                $questions[] = $this->generateMCQ($sentence, $generateDistractors, $includeCitations, $i + 1, $content);
            } elseif ($type === 'tf') {
                $questions[] = $this->generateTrueFalse($sentence, $includeCitations, $i + 1);
            } else {
                $questions[] = $this->generateStem($sentence, $includeCitations, $i + 1);
            }
        }

        return $questions;
    }

    private function extractSentences(string $content): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        return array_filter(array_map('trim', $sentences), function($s) {
            return strlen($s) > 20;
        });
    }

    private function generateMCQ(string $content, bool $generateDistractors, bool $includeCitation, int $index, string $fullContent): array
    {
        $keyWords = $this->extractKeyWords($content);
        $correctAnswer = $this->extractMainConcept($content);
        
        $options = [$correctAnswer];
        
        if ($generateDistractors) {
            $allWords = $this->extractKeyWords($fullContent);
            $distractors = $this->generatePlausibleDistractors($correctAnswer, $allWords, $keyWords);
            $options = array_merge($options, $distractors);
            shuffle($options);
        }

        $question = [
            'id' => $index,
            'type' => 'mcq',
            'stem' => $this->createQuestionFromSentence($content),
            'options' => array_slice($options, 0, 4),
            'correct' => $correctAnswer,
        ];

        if ($includeCitation) {
            $question['citation'] = "Đoạn " . ($index * 2) . " trong tài liệu";
        }

        return $question;
    }

    private function generateTrueFalse(string $content, bool $includeCitation, int $index): array
    {
        $isTrue = rand(0, 1) === 1;
        
        $question = [
            'id' => $index,
            'type' => 'tf',
            'stem' => $isTrue ? $content : $this->modifyToFalse($content),
            'options' => ['Đúng', 'Sai'],
            'correct' => $isTrue ? 'Đúng' : 'Sai',
        ];

        if ($includeCitation) {
            $question['citation'] = "Đoạn " . ($index * 2) . " trong tài liệu";
        }

        return $question;
    }

    private function generateStem(string $content, bool $includeCitation, int $index): array
    {
        $question = [
            'id' => $index,
            'type' => 'stem',
            'stem' => "Dựa vào nội dung: \"{$content}\", hãy đặt một câu hỏi và trả lời.",
            'options' => [],
            'correct' => null,
        ];

        if ($includeCitation) {
            $question['citation'] = "Đoạn " . ($index * 2) . " trong tài liệu";
        }

        return $question;
    }

    private function extractKeyWords(string $content): array
    {
        $words = str_word_count($content, 1, 'àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ');
        $words = array_filter($words, function($w) {
            return strlen($w) > 3;
        });
        return array_slice(array_unique($words), 0, 10);
    }

    private function extractMainConcept(string $content): string
    {
        if (preg_match('/là (.+?)[\.!?,]/i', $content, $matches)) {
            return trim($matches[1]);
        }
        if (preg_match('/gọi là (.+?)[\.!?,]/i', $content, $matches)) {
            return trim($matches[1]);
        }
        $words = $this->extractKeyWords($content);
        return $words[0] ?? 'Khái niệm chính';
    }

    private function generatePlausibleDistractors(string $correct, array $allWords, array $keyWords): array
    {
        $distractors = [];
        
        $similarWords = array_filter($allWords, function($w) use ($correct) {
            return $w !== $correct && (similar_text($w, $correct) > 3 || levenshtein($w, $correct) < 5);
        });
        
        if (count($similarWords) > 0) {
            $distractors[] = $similarWords[array_rand($similarWords)];
        }
        
        $otherKeyWords = array_filter($keyWords, function($w) use ($correct) {
            return $w !== $correct;
        });
        
        if (count($otherKeyWords) > 0) {
            $distractors[] = $otherKeyWords[array_rand($otherKeyWords)];
        }
        
        $distractors[] = 'Tất cả các đáp án trên';
        $distractors[] = 'Không có đáp án nào đúng';
        
        return array_slice($distractors, 0, 3);
    }

    private function createQuestionFromSentence(string $sentence): string
    {
        if (preg_match('/là (.+?)[\.!?]/i', $sentence, $matches)) {
            return "Theo nội dung trên, " . $matches[1] . " là gì?";
        }
        if (preg_match('/(.+?) được định nghĩa/i', $sentence, $matches)) {
            return "Theo định nghĩa, " . trim($matches[1]) . " là gì?";
        }
        return "Theo nội dung trên, câu nào sau đây đúng?";
    }

    private function modifyToFalse(string $sentence): string
    {
        $replacements = [
            'là' => 'không phải là',
            'có' => 'không có',
            'được' => 'không được',
            'phải' => 'không phải',
        ];
        
        foreach ($replacements as $search => $replace) {
            $sentence = preg_replace('/\b' . preg_quote($search, '/') . '\b/i', $replace, $sentence, 1);
        }
        
        return $sentence;
    }
}
