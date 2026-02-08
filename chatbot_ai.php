<?php
include './db.php';

// Inisialisasi session jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generate session ID untuk chat
if (!isset($_SESSION['chat_session_id'])) {
    $_SESSION['chat_session_id'] = uniqid('chat_', true);
}
$session_id = $_SESSION['chat_session_id'];

class SmartChatbot {
    private $conn;
    private $session_id;
    private $context = [];
    
    public function __construct($conn, $session_id) {
        $this->conn = $conn;
        $this->session_id = $session_id;
        $this->loadContext();
    }
    
    // Fungsi utama untuk memproses pesan
    public function processMessage($message) {
        // Preprocessing pesan
        $message = $this->preprocessMessage($message);
        
        // Cari jawaban terbaik
        $answer = $this->findBestAnswer($message);
        
        // Jika tidak ditemukan, coba dengan NLP sederhana
        if (!$answer) {
            $answer = $this->getNLPAnswer($message);
        }
        
        // Update konteks percakapan
        $this->updateContext($message, $answer);
        
        // Simpan log percakapan
        $this->saveConversationLog($message, $answer);
        
        return $answer;
    }
    
    // Preprocessing pesan
    private function preprocessMessage($message) {
        // Lowercase
        $message = strtolower(trim($message));
        
        // Hapus karakter khusus
        $message = preg_replace('/[^\p{L}\p{N}\s]/u', '', $message);
        
        // Hapus kata penghubung yang umum
        $stopwords = ['dan', 'atau', 'tetapi', 'namun', 'jika', 'maka', 'yang', 'di', 'ke', 'dari', 'pada', 'untuk', 'dengan'];
        $words = explode(' ', $message);
        $words = array_diff($words, $stopwords);
        
        return implode(' ', $words);
    }
    
    // Cari jawaban terbaik dari database
    private function findBestAnswer($message) {
        // Split message menjadi keywords
        $keywords = explode(' ', $message);
        $keywords = array_filter($keywords, function($word) {
            return strlen($word) > 2;
        });
        
        if (empty($keywords)) {
            return false;
        }
        
        // Bangun query pencarian
        $search_terms = [];
        foreach ($keywords as $keyword) {
            $search_terms[] = "question LIKE '%" . mysqli_real_escape_string($this->conn, $keyword) . "%'";
            $search_terms[] = "keywords LIKE '%" . mysqli_real_escape_string($this->conn, $keyword) . "%'";
        }
        
        $query = "SELECT *, 
                  (MATCH(question, keywords) AGAINST('" . mysqli_real_escape_string($this->conn, $message) . "' IN NATURAL LANGUAGE MODE) * 10) as relevance_score,
                  (LENGTH(question) - LENGTH(REPLACE(LOWER(question), '" . mysqli_real_escape_string($this->conn, $keywords[0]) . "', ''))) / LENGTH('" . mysqli_real_escape_string($this->conn, $keywords[0]) . "') as keyword_count
                  FROM chatbot_questions 
                  WHERE status='aktif' 
                  AND (" . implode(' OR ', $search_terms) . ")
                  ORDER BY relevance_score DESC, priority DESC, keyword_count DESC
                  LIMIT 1";
        
        $result = mysqli_query($this->conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $answer = $this->processDynamicAnswer($row['answer']);
            
            // Tambah konteks jika relevansi tinggi
            if ($row['relevance_score'] > 0.5) {
                $this->context['last_topic'] = $row['category'];
                $this->context['last_question'] = $row['question'];
                $this->saveContext();
            }
            
            return $answer;
        }
        
        return false;
    }
    
    // NLP sederhana untuk pertanyaan yang tidak ditemukan
    private function getNLPAnswer($message) {
        // Deteksi intent
        $intent = $this->detectIntent($message);
        
        switch ($intent) {
            case 'greeting':
                $responses = [
                    "Halo! Saya asisten Desa Brakas Dajah. Ada yang bisa saya bantu?",
                    "Selamat datang! Siap membantu Anda dengan informasi desa.",
                    "Hai! Tanyakan apa saja tentang Desa Brakas Dajah ya!"
                ];
                return $responses[array_rand($responses)];
                
            case 'thanks':
                $responses = [
                    "Sama-sama! Senang bisa membantu.",
                    "Terima kasih kembali! Jika ada pertanyaan lain, silakan tanyakan.",
                    "Dengan senang hati membantu!"
                ];
                return $responses[array_rand($responses)];
                
            case 'help':
                return "Saya bisa membantu dengan informasi tentang: 
                1. Administrasi kependudukan (KTP, KK, surat-surat)
                2. Data penduduk dan statistik
                3. APBDes dan keuangan desa
                4. Bansos dan bantuan sosial
                5. Berita dan kegiatan desa
                6. Lokasi dan kontak penting
                
                Silakan tanyakan apa yang Anda butuhkan!";
                
            case 'unknown':
                // Coba cari berdasarkan konteks sebelumnya
                if (isset($this->context['last_topic'])) {
                    $follow_up = $this->getFollowUpAnswer($this->context['last_topic']);
                    if ($follow_up) {
                        return $follow_up;
                    }
                }
                
                $responses = [
                    "Maaf, saya belum paham pertanyaan Anda. Coba tanyakan dengan kata kunci yang lebih spesifik.",
                    "Saya belum bisa menjawab pertanyaan itu. Mungkin Anda bisa menanyakan tentang layanan administrasi, data penduduk, atau bansos desa?",
                    "Untuk pertanyaan tersebut, silakan hubungi kantor desa langsung di 082150208664 untuk informasi lebih akurat."
                ];
                return $responses[array_rand($responses)];
        }
        
        return "Maaf, saya belum bisa menjawab pertanyaan itu. Silakan hubungi kantor desa untuk informasi lebih lanjut.";
    }
    
    // Deteksi intent dari pesan
    private function detectIntent($message) {
        $greeting_patterns = ['halo', 'hai', 'hi', 'selamat', 'pagi', 'siang', 'sore', 'malam'];
        $thanks_patterns = ['terima kasih', 'thanks', 'makasih', 'trimakasih'];
        $help_patterns = ['bantuan', 'help', 'tolong', 'bisa apa', 'fitur apa'];
        
        foreach ($greeting_patterns as $pattern) {
            if (strpos($message, $pattern) !== false) {
                return 'greeting';
            }
        }
        
        foreach ($thanks_patterns as $pattern) {
            if (strpos($message, $pattern) !== false) {
                return 'thanks';
            }
        }
        
        foreach ($help_patterns as $pattern) {
            if (strpos($message, $pattern) !== false) {
                return 'help';
            }
        }
        
        return 'unknown';
    }
    
    // Ambil jawaban follow-up berdasarkan konteks
    private function getFollowUpAnswer($last_topic) {
        $queries = [
            'administrasi' => "Apakah Anda ingin tahu tentang: 
            1. Cara mengurus KTP
            2. Cara membuat KK baru
            3. Syarat surat keterangan
            4. Biaya administrasi
            5. Waktu proses pengurusan",
            
            'penduduk' => "Ingin informasi lebih lanjut tentang:
            1. Jumlah penduduk per dusun
            2. Data usia produktif
            3. Kepadatan penduduk
            4. Pendidikan dan pekerjaan
            5. Migrasi penduduk",
            
            'apbdes' => "Tentang APBDes, Anda bisa tanyakan:
            1. Sumber pendapatan desa
            2. Alokasi belanja
            3. Proyek pembangunan
            4. Laporan keuangan
            5. Rencana tahun depan",
            
            'bansos' => "Tentang bansos:
            1. Jenis bansos yang tersedia
            2. Syarat penerima bansos
            3. Jadwal distribusi
            4. Cara daftar bansos
            5. Pengaduan bansos"
        ];
        
        return $queries[$last_topic] ?? false;
    }
    
    // Proses jawaban dengan data dinamis
    private function processDynamicAnswer($answer) {
        // Ganti placeholder dengan data real
        $placeholders = [
            '[total_penduduk]' => function() {
                $stat = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT total_penduduk FROM statistik_penduduk ORDER BY id DESC LIMIT 1")
                );
                return number_format($stat['total_penduduk'] ?? 0);
            },
            '[laki_laki]' => function() {
                $stat = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT laki_laki FROM statistik_penduduk ORDER BY id DESC LIMIT 1")
                );
                return number_format($stat['laki_laki'] ?? 0);
            },
            '[perempuan]' => function() {
                $stat = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT perempuan FROM statistik_penduduk ORDER BY id DESC LIMIT 1")
                );
                return number_format($stat['perempuan'] ?? 0);
            },
            '[kepala_keluarga]' => function() {
                $stat = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT kepala_keluarga FROM statistik_penduduk ORDER BY id DESC LIMIT 1")
                );
                return number_format($stat['kepala_keluarga'] ?? 0);
            },
            '[apbdes_pendapatan]' => function() {
                $apb = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT pendapatan FROM apbdes ORDER BY tahun DESC LIMIT 1")
                );
                return number_format($apb['pendapatan'] ?? 0, 0, ',', '.');
            },
            '[apbdes_belanja]' => function() {
                $apb = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT belanja FROM apbdes ORDER BY tahun DESC LIMIT 1")
                );
                return number_format($apb['belanja'] ?? 0, 0, ',', '.');
            },
            '[nama_kades]' => function() {
                $kades = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT nama_kades FROM sambutan WHERE status='aktif' LIMIT 1")
                );
                return $kades['nama_kades'] ?? 'Bahrudin';
            },
            '[jabatan]' => function() {
                $kades = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT jabatan FROM sambutan WHERE status='aktif' LIMIT 1")
                );
                return $kades['jabatan'] ?? 'Kepala Desa';
            },
            '[visi_desa]' => function() {
                $visi = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT visi FROM visi_misi WHERE status='aktif' LIMIT 1")
                );
                return strip_tags($visi['visi'] ?? 'Desa yang maju dan sejahtera');
            },
            '[misi_desa]' => function() {
                $misi = mysqli_fetch_assoc(
                    mysqli_query($this->conn, "SELECT misi FROM visi_misi WHERE status='aktif' LIMIT 1")
                );
                return strip_tags($misi['misi'] ?? 'Meningkatkan pelayanan publik');
            },
            '[latest_news]' => function() {
                $news = mysqli_query($this->conn, 
                    "SELECT judul FROM berita WHERE status='publish' ORDER BY tanggal DESC LIMIT 3"
                );
                $news_list = '';
                while($row = mysqli_fetch_assoc($news)) {
                    $news_list .= "• " . $row['judul'] . "\n";
                }
                return $news_list ?: 'Tidak ada berita terbaru saat ini.';
            },
            '[upcoming_events]' => function() {
                $events = mysqli_query($this->conn, 
                    "SELECT judul, tanggal FROM galeri WHERE kategori='agenda' AND status='aktif' AND tanggal >= CURDATE() ORDER BY tanggal ASC LIMIT 3"
                );
                $event_list = '';
                while($row = mysqli_fetch_assoc($events)) {
                    $date = date('d M Y', strtotime($row['tanggal']));
                    $event_list .= "• " . $row['judul'] . " (" . $date . ")\n";
                }
                return $event_list ?: 'Tidak ada agenda terdekat.';
            }
        ];
        
        foreach ($placeholders as $placeholder => $callback) {
            if (strpos($answer, $placeholder) !== false) {
                $answer = str_replace($placeholder, $callback(), $answer);
            }
        }
        
        return $answer;
    }
    
    // Load konteks dari database
    private function loadContext() {
        $query = "SELECT context_data FROM chatbot_context WHERE session_id = '" . mysqli_real_escape_string($this->conn, $this->session_id) . "'";
        $result = mysqli_query($this->conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $this->context = json_decode($row['context_data'], true) ?: [];
        }
    }
    
    // Simpan konteks ke database
    private function saveContext() {
        $context_json = mysqli_real_escape_string($this->conn, json_encode($this->context));
        $query = "INSERT INTO chatbot_context (session_id, context_data) 
                  VALUES ('" . mysqli_real_escape_string($this->conn, $this->session_id) . "', '$context_json')
                  ON DUPLICATE KEY UPDATE context_data = '$context_json', last_interaction = NOW()";
        mysqli_query($this->conn, $query);
    }
    
    // Update konteks dengan percakapan terbaru
    private function updateContext($user_message, $bot_response) {
        if (!isset($this->context['conversation'])) {
            $this->context['conversation'] = [];
        }
        
        // Simpan 5 percakapan terakhir
        $this->context['conversation'][] = [
            'user' => $user_message,
            'bot' => substr($bot_response, 0, 100),
            'time' => date('H:i')
        ];
        
        // Batasi hanya 5 percakapan terakhir
        if (count($this->context['conversation']) > 5) {
            array_shift($this->context['conversation']);
        }
        
        $this->saveContext();
    }
    
    // Simpan log percakapan
    private function saveConversationLog($user_message, $bot_response) {
        $user_message_escaped = mysqli_real_escape_string($this->conn, $user_message);
        $bot_response_escaped = mysqli_real_escape_string($this->conn, $bot_response);
        
        $query = "INSERT INTO chatbot_logs (session_id, user_message, bot_response) 
                  VALUES ('" . mysqli_real_escape_string($this->conn, $this->session_id) . "', 
                          '$user_message_escaped', 
                          '$bot_response_escaped')";
        mysqli_query($this->conn, $query);
    }
    
    // Fungsi untuk mendapatkan saran pertanyaan
    public function getSuggestedQuestions($limit = 5) {
        $query = "SELECT question FROM chatbot_questions 
                  WHERE status='aktif' 
                  ORDER BY priority DESC, RAND() 
                  LIMIT $limit";
        $result = mysqli_query($this->conn, $query);
        
        $questions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $questions[] = $row['question'];
        }
        
        return $questions;
    }
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'send_message':
            $message = trim($_POST['message']);
            $chatbot = new SmartChatbot($conn, $session_id);
            $response = $chatbot->processMessage($message);
            
            echo json_encode([
                'status' => 'success',
                'response' => $response
            ]);
            break;
            
        case 'get_suggestions':
            $chatbot = new SmartChatbot($conn, $session_id);
            $suggestions = $chatbot->getSuggestedQuestions(5);
            
            echo json_encode([
                'status' => 'success',
                'suggestions' => $suggestions
            ]);
            break;
            
        case 'get_context':
            $chatbot = new SmartChatbot($conn, $session_id);
            echo json_encode([
                'status' => 'success',
                'context' => $chatbot->getContext()
            ]);
            break;
            
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
    exit;
}
?>