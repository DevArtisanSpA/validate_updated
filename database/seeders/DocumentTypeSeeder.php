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
        $types = [
            ['Anexo de Contrato', 1, 1],
            ['Carta de Aviso', 1, 1],
            ['Cédula de Identidad ', 1, 1],
            ['Contrato de Trabajo', 1, 1],
            ['Credencial OS 21', 1, 1],
            ['Cursos', 1, 1],
            ['DAS/ODIS/Políticas', 1, 1],
            ['Examen de Altura', 1, 1],
            ['Examen OSP', 1, 1],
            ['Finiquito', 1, 1],
            ['Licencia de Conducir', 1, 1],
            ['Políticas de Seguridad', 1, 1],
            ['Recepción de EPP', 1, 1],
            ['Seguros de Vida', 1, 1],
            ['Toma de Conocimiento Registro Interno', 1, 1],
            ['Comprobante de Pago', 2, 1],
            ['AFP', 2, 1],
            ['Cajas de Compensación', 2, 1],
            ['Previsión', 2, 1],
            ['Mutualidad', 2, 1],
            ['Libro de Asistencia', 2, 1],
            ['Libro de Remuneraciones', 2, 1],
            ['Liquidación', 2, 1],
            ['Afiliación Mutualidad', 1, 2],
            ['Contrato Comercial', 1, 2],
            ['Orden de Compra', 1, 2],
            ['Políticas de Seguridad', 1, 2],
            ['Reg.Interno de Orden, Higiene y Seguridad', 1, 2],
            ['Formulario F31', 2, 2],
            ['Formulario F31-2', 2, 2],
            ['Libro de Remuneraciones', 2, 2],
            ['Cert. Siniestralidad', 2, 2],
            ['Certificado Empresa', 1, 2],
            ['Certificado Empleado', 1, 1],
            ['Conocimiento EC', 1, 2],
            ['Permisos/Acreditaciones', 1, 2],
            ['Certificado de antecedentes', 1, 1],
            ['Examenes ocupacionales', 1, 1],
            ['Cotizaciones',2,2],
            ['Certificado Validate',2,2],
            ['Informe Validate',2,2],
            ['Examen de Hemograma', 1, 1],
            ['Examen de Altura Geografica', 1, 1],
            ['Otros Examenes', 1, 1],

        ];
        foreach ($types as $index => $type) {
            DB::table('document_types')->insert([
                'id' => $index + 1,
                'temporality_id'=> $type[1],
                'area_id' =>$type[2],
                'name' => $type[0],
            ]);
        }
    }
}
