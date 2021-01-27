<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // 'temporality_id'=> $type[1] base 1 mensual 2
    //  'area_id' =>$type[2] empleado 1 empresa 2
    // 'service_id'=>$type[3] 	Fijo 1	Eventual 2	Mantenimiento 3
    // optional=>$type[3]
    $types = [
      ['Contrato de Trabajo', 1, 1, 1, false],
      ['Contrato de Trabajo', 1, 1, 2, false],
      ['Contrato de Trabajo', 1, 1, 3, false],
      ['Cédula de Identidad', 1, 1, 1, false],
      ['Cédula de Identidad', 1, 1, 2, false],
      ['Cédula de Identidad', 1, 1, 3, false],
      ['Anexo de Contrato', 1, 1, 3, true],
      ['Anexo de Contrato', 1, 1, 3, true],
      ['Anexo de Contrato', 1, 1, 3, true],
      ['Recepción de EPP', 1, 1, 1, false],
      ['Recepción de EPP', 1, 1, 2, false],
      ['Recepción de EPP', 1, 1, 3, false],
      ['DAS/ODIS/Políticas', 1, 1, 1, false],
      ['DAS/ODIS/Políticas', 1, 1, 2, false],
      ['DAS/ODIS/Políticas', 1, 1, 3, false],
      ['Toma de Conocimiento Reglamento Interno', 1, 1, 1, false],
      ['Toma de Conocimiento Reglamento Interno', 1, 1, 2, false],
      ['Toma de Conocimiento Reglamento Interno', 1, 1, 3, false],
      ['Carta de Aviso', 1, 1, 1, true],
      ['Carta de Aviso', 1, 1, 2, true],
      ['Carta de Aviso', 1, 1, 3, true],
      ['Credencial OS 21', 1, 1, 1, true],
      ['Credencial OS 21', 1, 1, 2, true],
      ['Credencial OS 21', 1, 1, 3, true],
      ['Cursos', 1, 1, 1, true],
      ['Cursos', 1, 1, 2, true],
      ['Cursos', 1, 1, 3, true],
      ['Examen de Altura', 1, 1, 1, true],
      ['Examen de Altura', 1, 1, 2, true],
      ['Examen de Altura', 1, 1, 3, true],
      ['Examen OSP', 1, 1, 1, true],
      ['Examen OSP', 1, 1, 2, true],
      ['Examen OSP', 1, 1, 3, true],
      ['Licencia de Conducir', 1, 1, 1, true],
      ['Licencia de Conducir', 1, 1, 2, true],
      ['Licencia de Conducir', 1, 1, 3, true],
      ['Seguros de Vida', 1, 1, 1, true],
      ['Seguros de Vida', 1, 1, 2, true],
      ['Seguros de Vida', 1, 1, 3, true],
      ['Certificado de antecedentes', 1, 1, 1, true],
      ['Certificado de antecedentes', 1, 1, 2, true],
      ['Certificado de antecedentes', 1, 1, 3, true],
      ['Examen de Hemograma', 1, 1, 1, true],
      ['Examen de Hemograma', 1, 1, 2, true],
      ['Examen de Hemograma', 1, 1, 3, true],
      ['Examen de Altura Geografica', 1, 1, 1, true],
      ['Examen de Altura Geografica', 1, 1, 2, true],
      ['Examen de Altura Geografica', 1, 1, 3, true],
      ['Otros Examenes', 1, 1, 1, true],
      ['Otros Examenes', 1, 1, 2, true],
      ['Otros Examenes', 1, 1, 3, true],
      ['Certificado Empleado', 1, 1, 1, true],
      ['Certificado Empleado', 1, 1, 2, true],
      ['Certificado Empleado', 1, 1, 3, true],
      ['Examenes ocupacionales', 1, 1, 1, true],
      ['Examenes ocupacionales', 1, 1, 2, true],
      ['Examenes ocupacionales', 1, 1, 3, true],
      ['Políticas de Seguridad', 1, 1, 1, true],
      ['Políticas de Seguridad', 1, 1, 2, true],
      ['Políticas de Seguridad', 1, 1, 3, true],

      // ['AFP', 2, 1],
      // ['Cajas de Compensación', 2, 1],
      // ['Previsión', 2, 1],
      // ['Mutualidad', 2, 1],
      // ['Libro de Remuneraciones', 2, 1],

      ['Liquidación', 2, 1, 1, false],
      ['Libro de Asistencia', 2, 1, 1, false],
      ['Comprobante de Pago', 2, 1, 1, false],
      ['Cotizaciones', 2, 1, 1, false],
      ['Finiquito', 2, 1, 1, true],
      ['Licencia Medica', 2, 1, 1, true],
      ['Traslado', 2, 1, 1, true],
      ['Contrato Comercial', 1, 2, 1, false],
      ['Contrato Comercial', 1, 2, 3, false],
      ['Orden de Compra', 1, 2, 2, false],
      ['Reg.Interno de Orden, Higiene y Seguridad', 1, 2, 1, false],
      ['Reg.Interno de Orden, Higiene y Seguridad', 1, 2, 2, false],
      ['Reg.Interno de Orden, Higiene y Seguridad', 1, 2, 3, false],
      ['Políticas de Seguridad', 1, 2, 1, true],
      ['Políticas de Seguridad', 1, 2, 2, true],
      ['Políticas de Seguridad', 1, 2, 3, true],
      ['Afiliación Mutualidad', 1, 2, 1, false],
      ['Afiliación Mutualidad', 1, 2, 2, false],
      ['Afiliación Mutualidad', 1, 2, 3, false],
      ['Permisos/Acreditaciones', 1, 2, 1, true],
      ['Permisos/Acreditaciones', 1, 2, 2, true],
      ['Permisos/Acreditaciones', 1, 2, 3, true],
      ['Formulario F30', 1, 2, 2, false],
      ['Formulario F30', 1, 2, 3, false],
      ['Cert. Siniestralidad', 1, 2, 2, false],
      ['Cert. Siniestralidad', 1, 2, 3, false],
      ['Certificado Empresa', 1, 2, 1, true],
      ['Certificado Empresa', 1, 2, 2, true],
      ['Certificado Empresa', 1, 2, 3, true],
      // ['Conocimiento EC', 1, 2],

      ['Formulario F30', 2, 2, 1, false],
      ['Cert. Siniestralidad', 2, 2, 1, false],
      ['Formulario F30-1', 2, 2, 1, false],
      ['Libro de Remuneraciones', 2, 2, 1, false],
      ['Certificado Validate', 2, 2, 1, true],
      ['Informe Validate', 2, 2, 1, true]

    ];
    foreach ($types as $index => $type) {
      try {
        DB::table('document_types')->updateOrInsert([
          'id' => $index + 1,
          'name' => $type[0],
          'temporality_id' => $type[1],
          'area_id' => $type[2],
          'service_type_id' => $type[3],
          'optional' => $type[4],
        ]);
      } catch (\Throwable $th) {
        DB::table('document_types')->where('id', $index + 1)
          ->update([
            'name' => $type[0],
            'temporality_id' => $type[1],
            'area_id' => $type[2],
            'service_type_id' => $type[3],
            'optional' => $type[4],
          ]);
      }
    }
  }
}
