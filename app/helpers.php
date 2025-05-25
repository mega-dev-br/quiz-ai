<?php

use Carbon\Carbon;

if (! function_exists('formatBytes')) {
  /**
   * Converte um tamanho em bytes para uma string legível.
   *
   * @param  int   $bytes     Número de bytes
   * @param  int   $precision Casas decimais
   * @return string
   */
  function formatBytes(int $bytes, int $precision = 2): string
  {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    if ($bytes === 0) {
      return '0 B';
    }

    $pow = (int) floor(log($bytes, 1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow)); // 1024**$pow
    return round($bytes, $precision) . ' ' . $units[$pow];
  }
}



/**
 * Formata data/hora em "d/m/Y H:i", aceitando tanto strings legíveis
 * quanto timestamps Unix puros.
 *
 * @param  mixed  $value  Timestamp (int ou numeric-string) ou string de data
 * @return string
 */
if (! function_exists('formatDate')) {
  function formatDate($value): string
  {
    // se veio só um número (timestamp), cria direto a partir dele
    if (is_numeric($value)) {
      $dt = Carbon::createFromTimestamp((int) $value);
    } else {
      $dt = Carbon::parse($value);
    }

    return $dt->format('d/m/Y H:i');
  }
}
