<?php

namespace App\Idcts;

/**
 * @property int $id
 * @property string $description
 * @property string $abbreviation
 */
final class DifficultiesIdct
{

  private static function baseDeDados()
  {

    $dados = [

      (object) ['id' => '1',  'description' => 'Fácil'],
      (object) ['id' => '2',  'description' => 'Médio'],
      (object) ['id' => '3',  'description' => 'Difícil'],

    ];

    return collect($dados);
  }

  public static function all()
  {
    return DifficultiesIdct::baseDeDados();
  }

  public static function find(?string $id)
  {
    $baseDeDadosCollection = DifficultiesIdct::baseDeDados();

    $resultado = $baseDeDadosCollection->where('id', $id)->first();

    if (!empty($resultado)) {
      return $resultado;
    }

    return (object) ['id' => '', 'description' => ''];
  }
}
