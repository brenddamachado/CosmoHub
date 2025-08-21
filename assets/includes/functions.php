<?php
// includes/functions.php

// Função para formatar um valor como moeda brasileira
function formatar_moeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

// Função para redirecionar o usuário
function redirecionar($url) {
    header("Location: " . $url);
    exit();
}