<?php

namespace App\Helpers;

use App\User;

class Constants
{
  public static $COUNTRIES = [
    "Chile", "Perú", "Colombia", "Venezuela", "Argentina", "Haití", "Ecuador", "España", "Afganistán", "Albania", "Alemania", "Andorra", "Angola", "Antigua y Barbuda",
    "Arabia Saudita", "Argelia",  "Armenia", "Australia", "Austria", "Azerbaiyán", "Bahamas",
    "Bangladés", "Barbados", "Baréin", "Bélgica", "Belice", "Benín", "Bielorrusia", "Birmania", "Bolivia",
    "Bosnia y Herzegovina", "Botsuana", "Brasil", "Brunéi", "Bulgaria", "Burkina Faso", "Burundi", "Bután",
    "Cabo Verde", "Camboya", "Camerún", "Canadá", "Catar", "Chad",  "China", "Chipre", "Ciudad del Vaticano",
    "Comoras", "Corea del Norte", "Corea del Sur", "Costa de Marfil", "Costa Rica", "Croacia", "Cuba",
    "Dinamarca", "Dominica",  "Egipto", "El Salvador", "Emiratos Árabes Unidos", "Eritrea", "Eslovaquia",
    "Eslovenia",  "Estados Unidos", "Estonia", "Etiopía", "Filipinas", "Finlandia", "Fiyi", "Francia",
    "Gabón", "Gambia", "Georgia", "Ghana", "Granada", "Grecia", "Guatemala", "Guyana", "Guinea", "Guinea ecuatorial",
    "Guinea-Bisáu", "Honduras", "Hungría", "India", "Indonesia", "Irak", "Irán", "Irlanda", "Islandia",
    "Islas Marshall", "Islas Salomón", "Israel", "Italia", "Jamaica", "Japón", "Jordania", "Kazajistán", "Kenia",
    "Kirguistán", "Kiribati", "Kuwait", "Laos", "Lesoto", "Letonia", "Líbano", "Liberia", "Libia", "Liechtenstein",
    "Lituania", "Luxemburgo", "Madagascar", "Malasia", "Malaui", "Maldivas", "Malí", "Malta", "Marruecos", "Mauricio",
    "Mauritania", "México", "Micronesia", "Moldavia", "Mónaco", "Mongolia", "Montenegro", "Mozambique", "Namibia",
    "Nauru", "Nepal", "Nicaragua", "Níger", "Nigeria", "Noruega", "Nueva Zelanda", "Omán", "Países Bajos",
    "Pakistán", "Palaos", "Panamá", "Papúa Nueva Guinea", "Paraguay",  "Polonia", "Portugal", "Reino Unido",
    "República Centroafricana", "República Checa", "República de Macedonia", "República del Congo",
    "República Democrática del Congo", "República Dominicana", "República Sudafricana", "Ruanda", "Rumanía",
    "Rusia", "Samoa", "San Cristóbal y Nieves", "San Marino", "San Vicente y las Granadinas", "Santa Lucía",
    "Santo Tomé y Príncipe", "Senegal", "Serbia", "Seychelles", "Sierra Leona", "Singapur", "Siria", "Somalia",
    "Sri Lanka", "Suazilandia", "Sudán", "Sudán del Sur", "Suecia", "Suiza", "Surinam", "Tailandia", "Tanzania",
    "Tayikistán", "Timor Oriental", "Togo", "Tonga", "Trinidad y Tobago", "Túnez", "Turkmenistán", "Turquía",
    "Tuvalu", "Ucrania", "Uganda", "Uruguay", "Uzbekistán", "Vanuatu", "Vietnam", "Yemen", "Yibuti",
    "Zambia", "Zimbabue"
  ];

  public static $AFFILIATIONS = ['Mutual', 'ACHS', 'ASL', 'IST'];

  public static $DOC_TEMP = ["Fijo", "Mensual"];
  public static $DOC_AREA = ["Trabajador", "Empresa"];
  public static $BASE_URL_ZIP = ['Pendiente/', 'Revision/', 'Aprobado/', 'Rechazado/'];

  public static $DOC_EMPLOYEE_FIXED = [
    "default" => [3, 4, 7, 13, 15],
    /*"colegio" => [3, 4, 7, 13, 15, 34],
    5 => [3, 4, 7, 13, 15, 5],
    4 => [3, 4, 7, 13, 15, 5],
    2 => [3, 4, 7, 13, 15]*/
  ];
  public static $DOC_EMPLOYEE_MONTHLY = [
    "default" => [16, 21, 23],
  ];

  public static $DOC_COMPANY_FIXED = [
    "default" => [25, 28, 24],
    "1" => [25, 28, 24],
    "2" => [26, 28, 24],
    "3" => [25, 28, 24],
  ];
  public static $DOC_COMPANY_MONTHLY = [
    'default' => [29, 32],
    '1' => [29, 30, 31, 32],
    '2' => [29, 32],
    '3' => [29, 32]
  ];
  public static $DOC_COMPANY_CREATE = [
    "default" => [24, 25, 26, 28, 35, 27, 36, 45, 46],
    "1" => [24, 25, 28, 35, 27, 36],
    "2" => [24, 26, 28, 35, 27, 36, 45, 46],
    "3" => [24, 25, 28, 35, 27, 36, 45, 46],
  ];

  public static function getAdmin()
  {
    return User::find(1);
  }
}
