<?php
echo "Config file: " . php_ini_loaded_file();
echo "<br>";
echo "Loaded extensions:<br>";
print_r(get_loaded_extensions());
?>
