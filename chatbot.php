<?php
include './db.php';

// Fungsi untuk mencari jawaban
function getChatbotAnswer($question, $conn) {
    $question = mysqli_real_escape_string($conn, $question);
    
    // Cari pertanyaan yang mirip
    $query = "SELECT * FROM chatbot_questions 
              WHERE status='aktif' 
              AND question LIKE '%$question%'
              LIMIT 1";
    
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Ganti placeholder dengan data real
        if(strpos($row['answer'], '[total_penduduk]') !== false) {
            $stat = mysqli_fetch_assoc(
                mysqli_query($conn, "SELECT total_penduduk FROM statistik_penduduk ORDER BY id DESC LIMIT 1")
            );
            $total = $stat['total_penduduk'] ?? 0;
            $row['answer'] = str_replace('[total_penduduk]', number_format($total), $row['answer']);
        }
        
        return $row['answer'];
    }
    
    // Jika tidak ditemukan, cari berdasarkan kategori umum
    $defaultQuery = "SELECT answer FROM chatbot_questions 
                     WHERE category='umum' AND status='aktif' 
                     ORDER BY RAND() LIMIT 1";
    $defaultResult = mysqli_query($conn, $defaultQuery);
    
    if(mysqli_num_rows($defaultResult) > 0) {
        $row = mysqli_fetch_assoc($defaultResult);
        return $row['answer'];
    }
    
    return "Maaf, saya belum bisa menjawab pertanyaan itu. Silakan hubungi kantor desa untuk informasi lebih lanjut.";
}

// Handle AJAX request
if(isset($_POST['action']) && $_POST['action'] == 'send_message') {
    $message = trim($_POST['message']);
    $response = getChatbotAnswer($message, $conn);
    
    echo json_encode([
        'status' => 'success',
        'response' => $response
    ]);
    exit;
}

// Get quick questions for buttons
$quickQuestions = [];
$query = "SELECT question FROM chatbot_questions 
          WHERE status='aktif' AND category != 'umum'
          ORDER BY RAND() LIMIT 4";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)) {
    $quickQuestions[] = $row['question'];
}
?>