<?php
try {
    include __DIR__ . '/../vendor/autoload.php';

    $options = getopt('i:o:f:', ['input:', 'output:', 'format:']);

    $input = $options['i'] ?? $options['input'] ?? '';
    $output = $options['o'] ?? $options['output'] ?? '';
    $format = $options['f'] ?? $options['format'] ?? '';

    $diagram = new Jawira\PhingDiagram\Diagram($input, $output, $format);
    $diagram->save();
} catch (Exception $ex) {
    $msg = $ex->getMessage();
    die($msg);
}
