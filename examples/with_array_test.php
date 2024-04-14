<?php



require_once __DIR__ .'/__bootstrap.php';

use LTL\Mapping\Mapping;

$example = new class extends Mapping {
    protected array $data = [
        'A VENCER' => 123,
        'RECEBIDO' => 222
    ];
};
