#!/usr/bin/env php
<?php
try {
    include __DIR__ . '/../vendor/autoload.php';

    $options = getopt('i:o:f:', ['input:', 'output:', 'format:']);

    $input = $options['i'] ?? $options['input'] ?? null;
    $output = $options['o'] ?? $options['output'] ?? null;
    $format = $options['f'] ?? $options['format'] ?? null;

    $diagram = new Jawira\PhingDiagram\Diagram($input, $output, $format);
    $diagram->save();
} catch (Exception $ex) {
    $msg = $ex->getMessage();
    die($msg);
}
