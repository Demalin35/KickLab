<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["index"])) {
    $index = (int) $_POST["index"];
    if (isset($_SESSION["cart"][$index])) {
        unset($_SESSION["cart"][$index]);
        $_SESSION["cart"] = array_values($_SESSION["cart"]); // reindex
    }
}
header("Location: " . $_SERVER["HTTP_REFERER"]);
exit;
