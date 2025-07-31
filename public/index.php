<?php include '../includes/config.php'; ?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EduTrack | Attendance System</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4cc9f0;
      --secondary: #4361ee;
      --dark: #1e1e2f;
      --light: #f8f9fa;
      --danger: #ef233c;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      background: linear-gradient(135deg, #4cc9f0, #4361ee);
      color: #fff;
      animation: fadeIn 1s ease-in-out;
      padding: 2rem;
    }

    header {
      margin-bottom: 2rem;
    }

    .logo {
      font-size: 2.5rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 0.5rem;
      width: 100%;
      max-width: 1200px;
      position: absolute;
      top: 1.2rem;
      left: 1.2rem;
    }

    .logo span {
      font-size: 1.5rem;
      font-weight: 600;
    }

    h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      animation: slideDown 1s ease forwards;
    }

    p {
      font-size: 1.1rem;
      opacity: 0.9;
      animation: fadeIn 1.5s ease forwards;
    }

    .cta-buttons {
      margin-top: 3rem;
      display: flex;
      flex-direction: row;
      gap: 1.5rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .btn {
      padding: 0.9rem 2rem;
      border: none;
      border-radius: 30px;
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
      background-color: #fff;
      color: var(--secondary);
    }

    .btn-primary:hover {
      background-color: var(--light);
      transform: translateY(-2px);
    }

    .btn-secondary {
      background-color: transparent;
      color: #fff;
      border: 2px solid #fff;
    }

    .btn-secondary:hover {
      background-color: #fff;
      color: var(--secondary);
      transform: translateY(-2px);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      h1 {
        font-size: 2rem;
      }

      .btn {
        width: 100%;
        padding: 1rem;
      }

      .cta-buttons {
        flex-direction: column;
        gap: 1rem;
      }

      .logo {
        font-size: 2rem;
        top: 1rem;
        left: 1rem;
      }

      .logo span {
        font-size: 1.2rem;
      }
    }
  </style>
</head>
<body>
  <div class="logo">
    📚 <span>EduTrack</span>
  </div>

  <header>
    <h1>Welcome to EduTrack</h1>
    <p>Track attendance for students and staff with ease and efficiency.</p>
  </header>

  <main>
    <div class="cta-buttons">
      <a href="login.php" class="btn btn-primary">Login</a>
      <a href="signup.php" class="btn btn-secondary">Register</a>
    </div>
  </main>
</body>
</html>
