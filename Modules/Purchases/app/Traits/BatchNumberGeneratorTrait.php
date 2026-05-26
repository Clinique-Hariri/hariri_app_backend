<?php
namespace Modules\Purchases\Traits;

use InvalidArgumentException;


trait BatchNumberGeneratorTrait
{

  function generateBatchNumber(string $type, int $referenceId): string
  {

      $prefixMap = [
          'manual'   => 'MNL',
          'purchase' => 'PUR',
          'return'   => 'RTN',
      ];

      if (!array_key_exists($type, $prefixMap)) {
          throw new InvalidArgumentException("Invalid batch type: $type");
      }

      $prefix = $prefixMap[$type];
      $date = date('ymd');
      $unique = strtoupper(substr(md5(uniqid((string)$referenceId, true)), 0, 4));

      return "{$prefix}-{$referenceId}-{$date}-{$unique}";
  }
}
