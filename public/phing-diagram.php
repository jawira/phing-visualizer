<?php
try {
    include __DIR__. '/../vendor/autoload.php';
    $options = getopt('f:', ['buildfile:']);
    $path = reset($options);
    if (!is_file($path)) {
        throw new Exception('Invalid buildfile');
    }

    $diagram = new Jawira\PhingDiagram\Diagram($path);
    echo $diagram->getCode();

} catch (Exception $ex) {
    $msg = $ex->getMessage();
    die($msg);
}
