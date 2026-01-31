<?php
header('Content-Type: text/css');
?>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

.login-container {
    width: 100%;
    max-width: 550px;
    background-color: #ffffff;
    border: 4px solid #2c3e50;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.header-section {
    text-align: center;
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 3px solid #e8e8e8;
}

h1 {
    font-size: 38px;
    color: #2c3e50;
    margin-bottom: 12px;
    font-weight: bold;
    line-height: 1.2;
}

.subtitle {
    font-size: 18px;
    color: #555555;
    margin-top: 10px;
}

.form-group {
    margin-bottom: 28px;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 28px;
}

.form-row .form-group {
    flex: 1;
    margin-bottom: 0;
}

label {
    display: block;
    font-size: 20px;
    color: #2c3e50;
    margin-bottom: 10px;
    font-weight: bold;
    line-height: 1.3;
}

.helper-text {
    font-size: 15px;
    color: #777777;
    margin-bottom: 8px;
    font-style: italic;
    display: block;
}

input[type="text"],
input[type="password"],
input[type="number"] {
    width: 100%;
    padding: 16px;
    font-size: 18px;
    border: 3px solid #cccccc;
    border-radius: 8px;
    background-color: #ffffff;
    color: #2c3e50;
    transition: all 0.3s ease;
    line-height: 1.5;
}

input[type="text"]::placeholder,
input[type="password"]::placeholder,
input[type="number"]::placeholder {
    color: #cccccc;
    font-size: 17px;
}

input[type="text"]:focus,
input[type="password"]:focus,
input[type="number"]:focus {
    outline: none;
    border-color: #2c7aa5;
    box-shadow: 0 0 8px rgba(44, 122, 165, 0.4);
    background-color: #f9f9f9;
}

button {
    width: 100%;
    padding: 18px;
    font-size: 20px;
    font-weight: bold;
    color: #ffffff;
    background-color: #2c7aa5;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 10px;
    transition: all 0.3s ease;
    line-height: 1.4;
}

button:hover {
    background-color: #1e5a7f;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(44, 122, 165, 0.3);
}

button:focus {
    outline: 3px solid #2c7aa5;
    outline-offset: 2px;
}

button:active {
    transform: translateY(0);
}

.btn-primary {
    background-color: #27ae60;
    font-size: 22px;
    margin-top: 20px;
}

.btn-primary:hover {
    background-color: #1e8449;
}

.btn-secondary {
    background-color: #3498db;
    font-size: 18px;
}

.btn-secondary:hover {
    background-color: #2980b9;
}

.error-message {
    background-color: #ffe6e6;
    border: 3px solid #e74c3c;
    color: #c0392b;
    padding: 16px;
    margin-bottom: 25px;
    font-size: 17px;
    font-weight: bold;
    border-radius: 8px;
    line-height: 1.5;
}

.success-message {
    background-color: #e6ffe6;
    border: 3px solid #27ae60;
    color: #196f3d;
    padding: 16px;
    margin-bottom: 25px;
    font-size: 17px;
    font-weight: bold;
    border-radius: 8px;
    line-height: 1.5;
}

.login-link-section {
    margin-top: 30px;
    padding-top: 25px;
    border-top: 3px solid #e8e8e8;
}

.login-text {
    font-size: 17px;
    color: #555555;
    margin-bottom: 15px;
    text-align: center;
}

.register-link {
    display: block;
    text-decoration: none;
    width: 100%;
}

.register-button {
    background-color: #e8e8e8;
    color: #2c3e50;
    border: 3px solid #2c3e50;
    font-weight: bold;
}

.register-button:hover {
    background-color: #d0d0d0;
}
