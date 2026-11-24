<?php
// Vercel Serverless Entry Point for CodeIgniter 4
putenv('VERCEL=1');
$_SERVER['VERCEL'] = '1';
$_SERVER['SCRIPT_NAME'] = '/index.php';

chdir(__DIR__ . '/../public');
require __DIR__ . '/../public/index.php';
