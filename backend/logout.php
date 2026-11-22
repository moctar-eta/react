<?php
session_start();
session_destroy();
header("Location: ../frontend/cpp.html");
exit;
