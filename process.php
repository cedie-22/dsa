<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST["submit"])) {
    $expression = trim($_POST["expression"]); 
    $conversion = $_POST["conversion"];

    function precedence($op) {
        if ($op == '+' || $op == '-') return 1;
        if ($op == '*' || $op == '/') return 2;
        return 0;
    }

    function infixToPostfix($exp) {
        $output = "";
        $stack = [];
        $exp = str_replace(" ", "", $exp); 
        $tokens = str_split($exp);

        foreach ($tokens as $token) {
            if (ctype_alpha($token) || is_numeric($token)) {
                $output .= $token . " "; 
            } elseif ($token == '(') {
                array_push($stack, $token);
            } elseif ($token == ')') {
                while (!empty($stack) && end($stack) != '(') {
                    $output .= array_pop($stack) . " ";
                }
                array_pop($stack);
            } else {
                while (!empty($stack) && precedence(end($stack)) >= precedence($token)) {
                    $output .= array_pop($stack) . " ";
                }
                array_push($stack, $token);
            }
        }

        while (!empty($stack)) {
            $output .= array_pop($stack) . " ";
        }

        return trim($output);
    }

    function infixToPrefix($exp) {
        $exp = strrev($exp);
        $exp = str_replace("(", "temp", $exp);
        $exp = str_replace(")", "(", $exp);
        $exp = str_replace("temp", ")", $exp);

        $postfix = infixToPostfix($exp);
        return strrev($postfix); 
    }

    function postfixToInfix($exp) {
        $stack = [];
        $exp = trim($exp);
        $tokens = explode(" ", $exp);

        foreach ($tokens as $token) {
            if (ctype_alpha($token) || is_numeric($token)) {
                array_push($stack, $token);
            } else {
                if (count($stack) < 2) return "Invalid Postfix Expression!";
                $op1 = array_pop($stack);
                $op2 = array_pop($stack);
                $newExp = "(" . $op2 . " " . $token . " " . $op1 . ")";
                array_push($stack, $newExp);
            }
        }

        return count($stack) == 1 ? end($stack) : "Invalid Postfix Expression!";
    }

    function prefixToInfix($exp) {
        $stack = [];
        $exp = trim($exp);
        $tokens = array_reverse(explode(" ", $exp));

        foreach ($tokens as $token) {
            if (ctype_alpha($token) || is_numeric($token)) {
                array_push($stack, $token);
            } else {
                if (count($stack) < 2) return "Invalid Prefix Expression!";
                $op1 = array_pop($stack);
                $op2 = array_pop($stack);
                $newExp = "(" . $op1 . " " . $token . " " . $op2 . ")";
                array_push($stack, $newExp);
            }
        }

        return count($stack) == 1 ? end($stack) : "Invalid Prefix Expression!";
    }

    $result = "";
    if ($conversion == "postfix") {
        $result = infixToPostfix($expression);
    } elseif ($conversion == "prefix") {
        $result = infixToPrefix($expression);
    } elseif ($conversion == "postfixToInfix") {
        $result = postfixToInfix($expression);
    } elseif ($conversion == "prefixToInfix") {
        $result = prefixToInfix($expression);
    } else {
        $result = "Invalid Conversion Type!";
    }

    echo "<h2>Converted Expression:</h2>";
    echo "<p><strong>Input Expression:</strong> " . htmlspecialchars($expression) . "</p>";
    echo "<p><strong>Converted Output:</strong> " . htmlspecialchars($result) . "</p>";
    echo "<br><a href='index.html'>Go Back</a>";
} else {
    echo "No input received.";
}
?>
