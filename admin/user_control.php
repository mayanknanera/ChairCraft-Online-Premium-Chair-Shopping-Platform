<?php
session_start();  
include 'config/db_con.php';

// <--------------------------------------------- Handle user logout ------------------------------->  
if(isset($_POST['btn_logout'])) {
    // session_destroy();
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    $_SESSION['message'] = "You have logged out successfully";
    header("Location: login.php");
    exit();
}

// <-------------------------------------------------------------- Handle user sign up ------------------------------>
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sign_up'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
    } elseif (!is_numeric($phone) || strlen($phone) < 10 || strlen($phone) > 15) {
        $_SESSION['error'] = "Invalid phone number";
    } elseif (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters";
    } elseif ($password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
    } else {
        // Check if email already exists using prepared statement
        $check_email_stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
        $check_email_stmt->bind_param("s", $email);
        $check_email_stmt->execute();
        $check_email_stmt->store_result();

        if ($check_email_stmt->num_rows > 0) {
            $_SESSION['error'] = "Email already exists";
            $check_email_stmt->close();
        } else {
            $check_email_stmt->close();

            // Hash the password before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user using prepared statement
            $insert_stmt = $con->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

            if ($insert_stmt->execute()) {
                $_SESSION['message'] = "Sign up successful!";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "Sign up failed: " . $con->error;
            }
            $insert_stmt->close();
        }
    }
}

// <-------------------------------------------------------- Handle user update ----------------------------------->
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = intval($_POST['user_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $role_as = isset($_POST['role_as']) ? $_POST['role_as'] : null;

    // Validate input
    if (empty($name) || empty($email) || empty($phone)) {
        $_SESSION['error'] = "Name, email, and phone are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
    } elseif (!is_numeric($phone) || strlen($phone) < 10 || strlen($phone) > 15) {
        $_SESSION['error'] = "Invalid phone number";
    } else {
        try {
            $con->begin_transaction();

            // Check if password is provided, else keep old
            if (!empty($password)) {
                // Hash the new password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $con->prepare("UPDATE users SET name = ?, email = ?, phone = ?, password = ? WHERE id = ?");
                $update_stmt->bind_param("ssssi", $name, $email, $phone, $hashed_password, $user_id);
            } else {
                $update_stmt = $con->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
                $update_stmt->bind_param("sssi", $name, $email, $phone, $user_id);
            }

            if ($update_stmt->execute()) {
                // Update session data
                $_SESSION['auth_user']['name'] = $name;
                $_SESSION['auth_user']['email'] = $email;
                $_SESSION['auth_user']['phone'] = $phone;

                // Check if self-update or admin update
                if (isset($_SESSION['auth_user']) && $_SESSION['auth_user']['id'] == $user_id) {
                    $con->commit();
                    $_SESSION['message'] = "Profile updated successfully";
                    header("Location: ../my-profile.php");
                    exit();
                } else {
                    // Update role if provided (admin update)
                    if ($role_as !== null) {
                        $role_stmt = $con->prepare("UPDATE users SET role_as = ? WHERE id = ?");
                        $role_stmt->bind_param("si", $role_as, $user_id);
                        $role_stmt->execute();
                        $role_stmt->close();
                    }

                    $con->commit();
                    $_SESSION['message'] = "User updated successfully";
                    header("Location: registered_users.php");
                    exit();
                }
            } else {
                throw new Exception("Error updating user: " . $con->error);
            }
            $update_stmt->close();
        } catch (Exception $e) {
            $con->rollback();
            $_SESSION['error'] = $e->getMessage();
            if (isset($_SESSION['auth_user']) && $_SESSION['auth_user']['id'] == $user_id) {
                header("Location: ../my-profile.php");
            } else {
                header("Location: registered_users.php?id=$user_id");
            }
            exit();
        }
    }
}

// <------------------------------------------------- Handle user login ----------------------------------->
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_user'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
    } else {
        // Check if user exists using prepared statement
        $login_stmt = $con->prepare("SELECT id, name, email, phone, password, role_as FROM users WHERE email = ?");
        $login_stmt->bind_param("s", $email);
        $login_stmt->execute();
        $result = $login_stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['auth'] = $user['role_as'];
                $_SESSION['auth_user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'role_as' => $user['role_as']
                ];
                $_SESSION['message'] = "Login successful";

                // Handle redirect after login
                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
                if (!empty($redirect)) {
                    // Use relative path for redirect
                    header("Location: " . $redirect);
                } else if ($user['role_as'] == '1') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Invalid email or password";
            }
        } else {
            $_SESSION['error'] = "Invalid email or password";
        }
        $login_stmt->close();
    }
}

?>  