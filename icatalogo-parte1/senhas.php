<?php

//testando os hashs

// echo md5("1234");

// echo "<br> <br>";

// echo sha1("1234");

// echo "<br> <br>";

echo password_hash("321#@!", PASSWORD_DEFAULT);