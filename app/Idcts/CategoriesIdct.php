<?php

namespace App\Idcts;

/**
 * @property int $id
 * @property string $description
 * @property string $abbreviation
 */
final class CategoriesIdct
{

  private static function baseDeDados()
  {

    $dados = [

      (object) ['id' => '1',  'description' => 'Antropologia'],
      (object) ['id' => '2',  'description' => 'Arquitetura'],
      (object) ['id' => '3',  'description' => 'Arte'],
      (object) ['id' => '4',  'description' => 'Astronomia'],
      (object) ['id' => '5',  'description' => 'Autoconhecimento'],
      (object) ['id' => '6',  'description' => 'Beleza'],
      (object) ['id' => '7',  'description' => 'Bem-estar'],
      (object) ['id' => '8',  'description' => 'Biologia'],
      (object) ['id' => '9',  'description' => 'Ciência'],
      (object) ['id' => '10', 'description' => 'Ciência da Computação'],
      (object) ['id' => '11', 'description' => 'Comunicação'],
      (object) ['id' => '12', 'description' => 'Culinária'],
      (object) ['id' => '13', 'description' => 'Desenvolvimento Pessoal'],
      (object) ['id' => '14', 'description' => 'Direito'],
      (object) ['id' => '15', 'description' => 'Economia'],
      (object) ['id' => '16', 'description' => 'Educação'],
      (object) ['id' => '17', 'description' => 'Empreendedorismo'],
      (object) ['id' => '18', 'description' => 'Engenharia'],
      (object) ['id' => '19', 'description' => 'Entretenimento'],
      (object) ['id' => '20', 'description' => 'Espiritualidade'],
      (object) ['id' => '21', 'description' => 'Esporte'],
      (object) ['id' => '22', 'description' => 'Fitness'],
      (object) ['id' => '23', 'description' => 'Filosofia'],
      (object) ['id' => '24', 'description' => 'Física'],
      (object) ['id' => '25', 'description' => 'Geografia'],
      (object) ['id' => '26', 'description' => 'Gestão'],
      (object) ['id' => '27', 'description' => 'História'],
      (object) ['id' => '28', 'description' => 'Inovação'],
      (object) ['id' => '29', 'description' => 'Inteligência Artificial'],
      (object) ['id' => '30', 'description' => 'Literatura'],
      (object) ['id' => '31', 'description' => 'Logística'],
      (object) ['id' => '32', 'description' => 'Marketing'],
      (object) ['id' => '33', 'description' => 'Matemática'],
      (object) ['id' => '34', 'description' => 'Medicina'],
      (object) ['id' => '35', 'description' => 'Moda'],
      (object) ['id' => '36', 'description' => 'Música'],
      (object) ['id' => '37', 'description' => 'Negócios'],
      (object) ['id' => '38', 'description' => 'Nutrição'],
      (object) ['id' => '39', 'description' => 'Psicologia'],
      (object) ['id' => '40', 'description' => 'Química'],
      (object) ['id' => '41', 'description' => 'Recursos Humanos'],
      (object) ['id' => '42', 'description' => 'Saúde'],
      (object) ['id' => '43', 'description' => 'Saúde Mental'],
      (object) ['id' => '44', 'description' => 'Sociologia'],
      (object) ['id' => '45', 'description' => 'Sustentabilidade'],
      (object) ['id' => '46', 'description' => 'Tecnologia'],
      (object) ['id' => '47', 'description' => 'Turismo'],
      (object) ['id' => '48', 'description' => 'Viagem'],

      // “Outros” sempre por último
      (object) ['id' => '9999', 'description' => 'Outros'],

    ];

    return collect($dados);
  }

  public static function all()
  {
    return CategoriesIdct::baseDeDados();
  }

  public static function find(?string $id)
  {
    $baseDeDadosCollection = CategoriesIdct::baseDeDados();

    $resultado = $baseDeDadosCollection->where('id', $id)->first();

    if (!empty($resultado)) {
      return $resultado;
    }

    return (object) ['id' => '', 'description' => ''];
  }
}
