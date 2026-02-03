<?php
$file = 'app/Http/Controllers/HomeController.php';
$content = file_get_contents($file);

// Find the start of billingPivotNetCarrier method
$start_pos = strpos($content, 'public function billingPivotNetCarrier');
if ($start_pos === false) {
    die("Method not found");
}

// Find the closing brace of the method (next "public function" or EOF)
$next_method_pos = strpos($content, 'public function billingPivotCountryCarrier', $start_pos);
if ($next_method_pos === false) {
    die("Next method not found");
}

// Go back to find the last closing brace before the next method
$closing_brace_pos = strrpos(substr($content, 0, $next_method_pos), '}');

// Extract the parts
$before = substr($content, 0, $start_pos);
$after = substr($content, $next_method_pos);

// Read the new method
$new_method = file_get_contents('billingPivotNetCarrier_new.php');

// Combine
$new_content = $before . $new_method . "\n\n    " . $after;

// Write back
file_put_contents($file, $new_content);
echo "Done!";
?>
