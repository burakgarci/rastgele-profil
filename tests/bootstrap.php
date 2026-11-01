<?php

require __DIR__ . '/../bootstrap.php';

/** Testler config.local.php'den etkilenmesin diye varsayılan ayarları kullanır. */
function test_ayarlari(array $ek = []): array
{
    return array_replace_recursive(require __DIR__ . '/../config.php', $ek);
}
