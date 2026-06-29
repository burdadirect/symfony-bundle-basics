<?php

namespace HBM\BasicsBundle\Util\Enum;

use HBM\BasicsBundle\Util\Enum\Interfaces\EnumInterface;
use HBM\BasicsBundle\Util\Enum\Traits\EnumTrait;

enum Country: string implements EnumInterface
{
    use EnumTrait;

    case AND = 'AD'; // Andorra
    case ARE = 'AE'; // United Arab Emirates
    case AFG = 'AF'; // Afghanistan
    case ATG = 'AG'; // Antigua and Barbuda
    case AIA = 'AI'; // Anguilla
    case ALB = 'AL'; // Albania
    case ARM = 'AM'; // Armenia
    case AGO = 'AO'; // Angola
    case ATA = 'AQ'; // Antarctica
    case ARG = 'AR'; // Argentina
    case ASM = 'AS'; // American Samoa
    case AUT = 'AT'; // Austria
    case AUS = 'AU'; // Australia
    case ABW = 'AW'; // Aruba
    case ALA = 'AX'; // Åland Islands
    case AZE = 'AZ'; // Azerbaijan
    case BIH = 'BA'; // Bosnia and Herzegovina
    case BRB = 'BB'; // Barbados
    case BGD = 'BD'; // Bangladesh
    case BEL = 'BE'; // Belgium
    case BFA = 'BF'; // Burkina Faso
    case BGR = 'BG'; // Bulgaria
    case BHR = 'BH'; // Bahrain
    case BDI = 'BI'; // Burundi
    case BEN = 'BJ'; // Benin
    case BLM = 'BL'; // Saint Barthélemy
    case BMU = 'BM'; // Bermuda
    case BRN = 'BN'; // Brunei Darussalam
    case BOL = 'BO'; // Bolivia (Plurinational State of)
    case BES = 'BQ'; // Bonaire, Sint Eustatius and Saba
    case BRA = 'BR'; // Brazil
    case BHS = 'BS'; // Bahamas
    case BTN = 'BT'; // Bhutan
    case BVT = 'BV'; // Bouvet Island
    case BWA = 'BW'; // Botswana
    case BLR = 'BY'; // Belarus
    case BLZ = 'BZ'; // Belize
    case CAN = 'CA'; // Canada
    case CCK = 'CC'; // Cocos (Keeling) Islands
    case COD = 'CD'; // Congo, Democratic Republic of the
    case CAF = 'CF'; // Central African Republic
    case COG = 'CG'; // Congo
    case CHE = 'CH'; // Switzerland
    case CIV = 'CI'; // Côte d'Ivoire
    case COK = 'CK'; // Cook Islands
    case CHL = 'CL'; // Chile
    case CMR = 'CM'; // Cameroon
    case CHN = 'CN'; // China
    case COL = 'CO'; // Colombia
    case CRI = 'CR'; // Costa Rica
    case SCG = 'CS'; // Serbia and Montenegro (historical)
    case CUB = 'CU'; // Cuba
    case CPV = 'CV'; // Cabo Verde
    case CUW = 'CW'; // Curaçao
    case CXR = 'CX'; // Christmas Island
    case CYP = 'CY'; // Cyprus
    case CZE = 'CZ'; // Czechia
    case DEU = 'DE'; // Germany
    case DJI = 'DJ'; // Djibouti
    case DNK = 'DK'; // Denmark
    case DMA = 'DM'; // Dominica
    case DOM = 'DO'; // Dominican Republic
    case DZA = 'DZ'; // Algeria
    case ECU = 'EC'; // Ecuador
    case EST = 'EE'; // Estonia
    case EGY = 'EG'; // Egypt
    case ESH = 'EH'; // Western Sahara
    case ERI = 'ER'; // Eritrea
    case ESP = 'ES'; // Spain
    case ETH = 'ET'; // Ethiopia
    case FIN = 'FI'; // Finland
    case FJI = 'FJ'; // Fiji
    case FLK = 'FK'; // Falkland Islands (Malvinas)
    case FSM = 'FM'; // Micronesia (Federated States of)
    case FRO = 'FO'; // Faroe Islands
    case FRA = 'FR'; // France
    case GAB = 'GA'; // Gabon
    case GBR = 'GB'; // United Kingdom of Great Britain and Northern Ireland
    case GRD = 'GD'; // Grenada
    case GEO = 'GE'; // Georgia
    case GUF = 'GF'; // French Guiana
    case GGY = 'GG'; // Guernsey
    case GHA = 'GH'; // Ghana
    case GIB = 'GI'; // Gibraltar
    case GRL = 'GL'; // Greenland
    case GMB = 'GM'; // Gambia
    case GIN = 'GN'; // Guinea
    case GLP = 'GP'; // Guadeloupe
    case GNQ = 'GQ'; // Equatorial Guinea
    case GRC = 'GR'; // Greece
    case SGS = 'GS'; // South Georgia and the South Sandwich Islands
    case GTM = 'GT'; // Guatemala
    case GUM = 'GU'; // Guam
    case GNB = 'GW'; // Guinea-Bissau
    case GUY = 'GY'; // Guyana
    case HKG = 'HK'; // Hong Kong
    case HMD = 'HM'; // Heard Island and McDonald Islands
    case HND = 'HN'; // Honduras
    case HRV = 'HR'; // Croatia
    case HTI = 'HT'; // Haiti
    case HUN = 'HU'; // Hungary
    case IDN = 'ID'; // Indonesia
    case IRL = 'IE'; // Ireland
    case ISR = 'IL'; // Israel
    case IMN = 'IM'; // Isle of Man
    case IND = 'IN'; // India
    case IOT = 'IO'; // British Indian Ocean Territory
    case IRQ = 'IQ'; // Iraq
    case IRN = 'IR'; // Iran (Islamic Republic of)
    case ISL = 'IS'; // Iceland
    case ITA = 'IT'; // Italy
    case JEY = 'JE'; // Jersey
    case JAM = 'JM'; // Jamaica
    case JOR = 'JO'; // Jordan
    case JPN = 'JP'; // Japan
    case KEN = 'KE'; // Kenya
    case KGZ = 'KG'; // Kyrgyzstan
    case KHM = 'KH'; // Cambodia
    case KIR = 'KI'; // Kiribati
    case COM = 'KM'; // Comoros
    case KNA = 'KN'; // Saint Kitts and Nevis
    case PRK = 'KP'; // Korea (Democratic People's Republic of)
    case KOR = 'KR'; // Korea, Republic of
    case KWT = 'KW'; // Kuwait
    case CYM = 'KY'; // Cayman Islands
    case KAZ = 'KZ'; // Kazakhstan
    case LAO = 'LA'; // Lao People's Democratic Republic
    case LBN = 'LB'; // Lebanon
    case LCA = 'LC'; // Saint Lucia
    case LIE = 'LI'; // Liechtenstein
    case LKA = 'LK'; // Sri Lanka
    case LBR = 'LR'; // Liberia
    case LSO = 'LS'; // Lesotho
    case LTU = 'LT'; // Lithuania
    case LUX = 'LU'; // Luxembourg
    case LVA = 'LV'; // Latvia
    case LBY = 'LY'; // Libya
    case MAR = 'MA'; // Morocco
    case MCO = 'MC'; // Monaco
    case MDA = 'MD'; // Moldova, Republic of
    case MNE = 'ME'; // Montenegro
    case MAF = 'MF'; // Saint Martin (French part)
    case MDG = 'MG'; // Madagascar
    case MHL = 'MH'; // Marshall Islands
    case MKD = 'MK'; // North Macedonia
    case MLI = 'ML'; // Mali
    case MMR = 'MM'; // Myanmar
    case MNG = 'MN'; // Mongolia
    case MAC = 'MO'; // Macao
    case MNP = 'MP'; // Northern Mariana Islands
    case MTQ = 'MQ'; // Martinique
    case MRT = 'MR'; // Mauritania
    case MSR = 'MS'; // Montserrat
    case MLT = 'MT'; // Malta
    case MUS = 'MU'; // Mauritius
    case MDV = 'MV'; // Maldives
    case MWI = 'MW'; // Malawi
    case MEX = 'MX'; // Mexico
    case MYS = 'MY'; // Malaysia
    case MOZ = 'MZ'; // Mozambique
    case NAM = 'NA'; // Namibia
    case NCL = 'NC'; // New Caledonia
    case NER = 'NE'; // Niger
    case NFK = 'NF'; // Norfolk Island
    case NGA = 'NG'; // Nigeria
    case NIC = 'NI'; // Nicaragua
    case NLD = 'NL'; // Netherlands
    case NOR = 'NO'; // Norway
    case NPL = 'NP'; // Nepal
    case NRU = 'NR'; // Nauru
    case NIU = 'NU'; // Niue
    case NZL = 'NZ'; // New Zealand
    case OMN = 'OM'; // Oman
    case PAN = 'PA'; // Panama
    case PER = 'PE'; // Peru
    case PYF = 'PF'; // French Polynesia
    case PNG = 'PG'; // Papua New Guinea
    case PHL = 'PH'; // Philippines
    case PAK = 'PK'; // Pakistan
    case POL = 'PL'; // Poland
    case SPM = 'PM'; // Saint Pierre and Miquelon
    case PCN = 'PN'; // Pitcairn
    case PRI = 'PR'; // Puerto Rico
    case PSE = 'PS'; // Palestine, State of
    case PRT = 'PT'; // Portugal
    case PLW = 'PW'; // Palau
    case PRY = 'PY'; // Paraguay
    case QAT = 'QA'; // Qatar
    case REU = 'RE'; // Réunion
    case ROU = 'RO'; // Romania
    case SRB = 'RS'; // Serbia
    case RUS = 'RU'; // Russian Federation
    case RWA = 'RW'; // Rwanda
    case SAU = 'SA'; // Saudi Arabia
    case SLB = 'SB'; // Solomon Islands
    case SYC = 'SC'; // Seychelles
    case SDN = 'SD'; // Sudan
    case SWE = 'SE'; // Sweden
    case SGP = 'SG'; // Singapore
    case SHN = 'SH'; // Saint Helena, Ascension and Tristan da Cunha
    case SVN = 'SI'; // Slovenia
    case SJM = 'SJ'; // Svalbard and Jan Mayen
    case SVK = 'SK'; // Slovakia
    case SLE = 'SL'; // Sierra Leone
    case SMR = 'SM'; // San Marino
    case SEN = 'SN'; // Senegal
    case SOM = 'SO'; // Somalia
    case SUR = 'SR'; // Suriname
    case STP = 'ST'; // Sao Tome and Principe
    case SLV = 'SV'; // El Salvador
    case SXM = 'SX'; // Sint Maarten (Dutch part)
    case SYR = 'SY'; // Syrian Arab Republic
    case SWZ = 'SZ'; // Eswatini
    case TCA = 'TC'; // Turks and Caicos Islands
    case TCD = 'TD'; // Chad
    case ATF = 'TF'; // French Southern Territories
    case TGO = 'TG'; // Togo
    case THA = 'TH'; // Thailand
    case TJK = 'TJ'; // Tajikistan
    case TKL = 'TK'; // Tokelau
    case TLS = 'TL'; // Timor-Leste
    case TKM = 'TM'; // Turkmenistan
    case TUN = 'TN'; // Tunisia
    case TON = 'TO'; // Tonga
    case TUR = 'TR'; // Türkiye
    case TTO = 'TT'; // Trinidad and Tobago
    case TUV = 'TV'; // Tuvalu
    case TWN = 'TW'; // Taiwan, Province of China
    case TZA = 'TZ'; // Tanzania, United Republic of
    case UKR = 'UA'; // Ukraine
    case UGA = 'UG'; // Uganda
    case UMI = 'UM'; // United States Minor Outlying Islands
    case USA = 'US'; // United States of America
    case URY = 'UY'; // Uruguay
    case UZB = 'UZ'; // Uzbekistan
    case VAT = 'VA'; // Holy See
    case VCT = 'VC'; // Saint Vincent and the Grenadines
    case VEN = 'VE'; // Venezuela (Bolivarian Republic of)
    case VGB = 'VG'; // Virgin Islands (British)
    case VIR = 'VI'; // Virgin Islands (U.S.)
    case VNM = 'VN'; // Viet Nam
    case VUT = 'VU'; // Vanuatu
    case WLF = 'WF'; // Wallis and Futuna
    case WSM = 'WS'; // Samoa
    case XKX = 'XK'; // Kosovo
    case YEM = 'YE'; // Yemen
    case MYT = 'YT'; // Mayotte
    case ZAF = 'ZA'; // South Africa
    case ZMB = 'ZM'; // Zambia
    case ZWE = 'ZW'; // Zimbabwe

    public function fields(): array
    {
        $data = match ($this) {
            self::CIV => [
              'aliases_de' => 'Elfenbeinküste',
              'aliases_en' => 'Ivory Coast',
            ],
            self::PSE => [
              'aliases_de' => ['Palästina', 'Westjordanland', 'Gazastreifen'],
              'aliases_en' => ['Palestine', 'West Bank', 'Gaza Strip'],
            ],
            self::USA => [
              'aliases_de'   => 'USA',
              'aliases_en'   => 'USA',
            ],
            default => [
              'aliases_de' => [],
              'aliases_en' => [],
            ]
        };

        $data['filter'] = [];

        if (in_array($this, [self::AUT, self::CHE, self::DEU])) {
            $data['filter'][] = 'DACH';
        }

        if (in_array($this, [self::BEL, self::LUX, self::NLD])) {
            $data['filter'][] = 'BX';
        }

        if (in_array($this, [
          self::AUT, self::BEL, self::BGR, self::CYP, self::CZE, self::DEU,
          self::DNK, self::EST, self::ESP, self::FIN, self::FRA, self::GRC,
          self::HRV, self::HUN, self::IRL, self::ITA, self::LTU, self::LUX,
          self::LVA, self::MLT, self::NLD, self::POL, self::PRT, self::ROU,
          self::SWE, self::SVN, self::SVK])) {
            $data['filter'][] = 'EU';
        }

        $data['iso2'] = $this->value;
        $data['iso3'] = $this->name;

        $localeBackup = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, locale_get_default());

        $value = str_replace(['Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'ß'], ['Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue', 'ss'], $this->label());
        $data['transliterated'] = iconv('UTF-8', 'ASCII//TRANSLIT', $value);

        setlocale(LC_CTYPE, $localeBackup);

        return $data;
    }

    public function iso2(): string { return $this->value; }
    public function iso3(): string { return $this->name; }

    public function label(?string $locale = null): ?string {
        $locale = $locale ?? locale_get_default();
        return \Locale::getDisplayRegion('-'.$this->iso2(), $locale);
    }

    public static function tryFromIso(?string $iso2orIso3): ?self
    {
        if ($iso2orIso3 === null) {
            return null;
        }
        $iso2orIso3 = strtoupper(trim($iso2orIso3));
        if (strlen($iso2orIso3) === 3) {
            try {
                //return self::{$iso2orIso3};
                return constant('self::'.$iso2orIso3);
            } catch(\Exception $e) {
                return null;
            }
        }

        return self::tryFrom($iso2orIso3);
    }

    public function fromIso(string $iso2orIso3): self
    {
        $iso2orIso3 = strtoupper(trim($iso2orIso3));
        if (strlen($iso2orIso3) === 3) {
            //return self::{$iso2orIso3};
            return constant('self::'.$iso2orIso3);
        }

        return self::from($iso2orIso3);
    }

}
