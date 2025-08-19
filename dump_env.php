<?php

foreach (getenv() as $key => $value) {
    echo "$key=$value\n";
}

foreach ($_SERVER as $key => $value) {
    if (is_string($value)) { // Filter out non-string values like arrays
        echo "SERVER_$key=$value\n";
    }
}

foreach ($_ENV as $key => $value) {
    if (is_string($value)) { // Filter out non-string values like arrays
        echo "ENV_$key=$value\n";
    }
}


