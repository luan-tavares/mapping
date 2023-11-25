<?php



require_once __DIR__ .'/__bootstrap.php';

use LTL\Mapping\Mapping;

$example = new class extends Mapping {
    public const a = 'Teste';

    public const b = 52;

    public const c = 1.5;
};

dd($example->map(function ($from, $to) {
    dump($from);
    
    return '----' . $to;
}));
