<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Check both tables
        foreach (['students', 'staff'] as $table) {
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = ($table === 'students') ? 'student' : 'staff';
                header("Location: ".BASE_URL."/dashboard.php");
                exit();
            }
        }
        
        $_SESSION['error'] = "Invalid credentials";
        header("Location: ".BASE_URL."/login.php");
        exit();
    }
    
    if ($action === 'signup') {
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'id_number' => $_POST['id_number'],
            'type' => $_POST['user_type']
        ];
        
        $table = ($data['type'] === 'student') ? 'students' : 'staff';
        $id_field = ($data['type'] === 'student') ? 'student_id' : 'staff_id';
        
        try {
            $stmt = $pdo->prepare("INSERT INTO $table (name, email, password_hash, $id_field) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['name'], $data['email'], $data['password'], $data['id_number']]);
            
            $_SESSION['success'] = "Registration successful! Please login";
            header("Location: ".BASE_URL."/login.php");
            exit();
        } catch(PDOException $e) {
            $_SESSION['error'] = "Registration failed: ". (str_contains($e->getMessage(), 'Duplicate')) ? "Email/ID exists" : "Server error";
            header("Location: ".BASE_URL."/signup.php");
            exit();
        }
    }
}
// Default redirect if accessed directly
header("Location: ".BASE_URL);
exit();
?>