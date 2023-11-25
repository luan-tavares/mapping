<?php



require_once __DIR__ .'/__bootstrap.php';

use LTL\Mapping\Mapping;

$example = new class extends Mapping {
    public const a = 'Teste';

    public const b = null;

    public const c = 1.5;
};


dd($example->filter(function ($from, $to) {
    return !is_null($to);
})->map(function ($from, $to) {
    return '----' . $to;
}));
