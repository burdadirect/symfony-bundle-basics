<?php

namespace HBM\BasicsBundle\Util\Enum;

use HBM\BasicsBundle\Util\Enum\Interfaces\EnumInterface;
use HBM\BasicsBundle\Util\Enum\Traits\EnumTrait;

enum Country: string implements EnumInterface
{
    use EnumTrait;

    case AND = 'AD';
    case ARE = 'AE';
    case AFG = 'AF';
    case ATG = 'AG';
    case AIA = 'AI';
    case ALB = 'AL';
    case ARM = 'AM';
    case ANT = 'AN';
    case AGO = 'AO';
    case ATA = 'AQ';
    case ARG = 'AR';
    case ASM = 'AS';
    case AUT = 'AT';
    case AUS = 'AU';
    case ABW = 'AW';
    case ALA = 'AX';
    case AZE = 'AZ';
    case BIH = 'BA';
    case BRB = 'BB';
    case BGD = 'BD';
    case BEL = 'BE';
    case BFA = 'BF';
    case BGR = 'BG';
    case BHR = 'BH';
    case BDI = 'BI';
    case BEN = 'BJ';
    case BLM = 'BL';
    case BMU = 'BM';
    case BRN = 'BN';
    case BOL = 'BO';
    case BES = 'BQ';
    case BRA = 'BR';
    case BHS = 'BS';
    case BTN = 'BT';
    case BVT = 'BV';
    case BWA = 'BW';
    case BLR = 'BY';
    case BLZ = 'BZ';
    case CAN = 'CA';
    case CCK = 'CC';
    case COD = 'CD';
    case CAF = 'CF';
    case COG = 'CG';
    case CHE = 'CH';
    case CIV = 'CI';
    case COK = 'CK';
    case CHL = 'CL';
    case CMR = 'CM';
    case CHN = 'CN';
    case COL = 'CO';
    case CRI = 'CR';
    case SCG = 'CS';
    case CUB = 'CU';
    case CPV = 'CV';
    case CUW = 'CW';
    case CXR = 'CX';
    case CYP = 'CY';
    case CZE = 'CZ';
    case DEU = 'DE';
    case DJI = 'DJ';
    case DNK = 'DK';
    case DMA = 'DM';
    case DOM = 'DO';
    case DZA = 'DZ';
    case ECU = 'EC';
    case EST = 'EE';
    case EGY = 'EG';
    case ESH = 'EH';
    case ERI = 'ER';
    case ESP = 'ES';
    case ETH = 'ET';
    case FIN = 'FI';
    case FJI = 'FJ';
    case FLK = 'FK';
    case FSM = 'FM';
    case FRO = 'FO';
    case FRA = 'FR';
    case GAB = 'GA';
    case GBR = 'GB';
    case GRD = 'GD';
    case GEO = 'GE';
    case GUF = 'GF';
    case GGY = 'GG';
    case GHA = 'GH';
    case GIB = 'GI';
    case GRL = 'GL';
    case GMB = 'GM';
    case GIN = 'GN';
    case GLP = 'GP';
    case GNQ = 'GQ';
    case GRC = 'GR';
    case SGS = 'GS';
    case GTM = 'GT';
    case GUM = 'GU';
    case GNB = 'GW';
    case GUY = 'GY';
    case HKG = 'HK';
    case HMD = 'HM';
    case HND = 'HN';
    case HRV = 'HR';
    case HTI = 'HT';
    case HUN = 'HU';
    case IDN = 'ID';
    case IRL = 'IE';
    case ISR = 'IL';
    case IMN = 'IM';
    case IND = 'IN';
    case IOT = 'IO';
    case IRQ = 'IQ';
    case IRN = 'IR';
    case ISL = 'IS';
    case ITA = 'IT';
    case JEY = 'JE';
    case JAM = 'JM';
    case JOR = 'JO';
    case JPN = 'JP';
    case KEN = 'KE';
    case KGZ = 'KG';
    case KHM = 'KH';
    case KIR = 'KI';
    case COM = 'KM';
    case KNA = 'KN';
    case PRK = 'KP';
    case KOR = 'KR';
    case KWT = 'KW';
    case CYM = 'KY';
    case KAZ = 'KZ';
    case LAO = 'LA';
    case LBN = 'LB';
    case LCA = 'LC';
    case LIE = 'LI';
    case LKA = 'LK';
    case LBR = 'LR';
    case LSO = 'LS';
    case LTU = 'LT';
    case LUX = 'LU';
    case LVA = 'LV';
    case LBY = 'LY';
    case MAR = 'MA';
    case MCO = 'MC';
    case MDA = 'MD';
    case MNE = 'ME';
    case MAF = 'MF';
    case MDG = 'MG';
    case MHL = 'MH';
    case MKD = 'MK';
    case MLI = 'ML';
    case MMR = 'MM';
    case MNG = 'MN';
    case MAC = 'MO';
    case MNP = 'MP';
    case MTQ = 'MQ';
    case MRT = 'MR';
    case MSR = 'MS';
    case MLT = 'MT';
    case MUS = 'MU';
    case MDV = 'MV';
    case MWI = 'MW';
    case MEX = 'MX';
    case MYS = 'MY';
    case MOZ = 'MZ';
    case NAM = 'NA';
    case NCL = 'NC';
    case NER = 'NE';
    case NFK = 'NF';
    case NGA = 'NG';
    case NIC = 'NI';
    case NLD = 'NL';
    case NOR = 'NO';
    case NPL = 'NP';
    case NRU = 'NR';
    case NIU = 'NU';
    case NZL = 'NZ';
    case OMN = 'OM';
    case PAN = 'PA';
    case PER = 'PE';
    case PYF = 'PF';
    case PNG = 'PG';
    case PHL = 'PH';
    case PAK = 'PK';
    case POL = 'PL';
    case SPM = 'PM';
    case PCN = 'PN';
    case PRI = 'PR';
    case PSE = 'PS';
    case PRT = 'PT';
    case PLW = 'PW';
    case PRY = 'PY';
    case QAT = 'QA';
    case REU = 'RE';
    case ROU = 'RO';
    case SRB = 'RS';
    case RUS = 'RU';
    case RWA = 'RW';
    case SAU = 'SA';
    case SLB = 'SB';
    case SYC = 'SC';
    case SDN = 'SD';
    case SWE = 'SE';
    case SGP = 'SG';
    case SHN = 'SH';
    case SVN = 'SI';
    case SJM = 'SJ';
    case SVK = 'SK';
    case SLE = 'SL';
    case SMR = 'SM';
    case SEN = 'SN';
    case SOM = 'SO';
    case SUR = 'SR';
    case STP = 'ST';
    case SLV = 'SV';
    case SXM = 'SX';
    case SYR = 'SY';
    case SWZ = 'SZ';
    case TCA = 'TC';
    case TCD = 'TD';
    case ATF = 'TF';
    case TGO = 'TG';
    case THA = 'TH';
    case TJK = 'TJ';
    case TKL = 'TK';
    case TLS = 'TL';
    case TKM = 'TM';
    case TUN = 'TN';
    case TON = 'TO';
    case TUR = 'TR';
    case TTO = 'TT';
    case TUV = 'TV';
    case TWN = 'TW';
    case TZA = 'TZ';
    case UKR = 'UA';
    case UGA = 'UG';
    case UMI = 'UM';
    case USA = 'US';
    case URY = 'UY';
    case UZB = 'UZ';
    case VAT = 'VA';
    case VCT = 'VC';
    case VEN = 'VE';
    case VGB = 'VG';
    case VIR = 'VI';
    case VNM = 'VN';
    case VUT = 'VU';
    case WLF = 'WF';
    case WSM = 'WS';
    case XKX = 'XK';
    case YEM = 'YE';
    case MYT = 'YT';
    case ZAF = 'ZA';
    case ZMB = 'ZM';
    case ZWE = 'ZW';

    public function fields(): array
    {
        $match = match ($this) {
            self::AND => [
              'de'   => 'Andorra',
              'en'   => 'Andorra',
            ],
            self::ARE => [
              'de'   => 'Vereinigte Arabische Emirate',
              'en'   => 'United Arab Emirates',
            ],
            self::AFG => [
              'de'   => 'Afghanistan',
              'en'   => 'Afghanistan',
            ],
            self::ATG => [
              'de'   => 'Antigua/Barbuda',
              'en'   => 'Antigua and Barbuda',
            ],
            self::AIA => [
              'de'   => 'Anguilla',
              'en'   => 'Anguilla',
            ],
            self::ALB => [
              'de'   => 'Albanien',
              'en'   => 'Albania',
            ],
            self::ARM => [
              'de'   => 'Armenien',
              'en'   => 'Armenia',
            ],
            self::ANT => [
              'de'   => 'Niederländische Antillen',
              'en'   => 'Netherlands Antilles',
            ],
            self::AGO => [
              'de'   => 'Angola',
              'en'   => 'Angola',
            ],
            self::ATA => [
              'de'   => 'Antarktis',
              'en'   => 'Antarctica',
            ],
            self::ARG => [
              'de'   => 'Argentinien',
              'en'   => 'Argentina',
            ],
            self::ASM => [
              'de'   => 'Amerikanisch-Samoa',
              'en'   => 'American Samoa',
            ],
            self::AUT => [
              'de'   => 'Österreich',
              'en'   => 'Austria',
              'filter' => ['EU', 'DACH'],
            ],
            self::AUS => [
              'de'   => 'Australien',
              'en'   => 'Australia',
            ],
            self::ABW => [
              'de'   => 'Aruba',
              'en'   => 'Aruba',
            ],
            self::ALA => [
              'de'   => 'Åland',
              'en'   => 'Åland',
            ],
            self::AZE => [
              'de'   => 'Aserbaidschan',
              'en'   => 'Azerbaijan',
            ],
            self::BIH => [
              'de'   => 'Bosnien/Herzegowina',
              'en'   => 'Bosnia and Herzegovina',
            ],
            self::BRB => [
              'de'   => 'Barbados',
              'en'   => 'Barbados',
            ],
            self::BGD => [
              'de'   => 'Bangladesh',
              'en'   => 'Bangladesh',
            ],
            self::BEL => [
              'de'   => 'Belgien',
              'en'   => 'Belgium',
              'filter' => ['EU', 'BX'],
            ],
            self::BFA => [
              'de'   => 'Burkina Faso',
              'en'   => 'Burkina Faso',
            ],
            self::BGR => [
              'de'   => 'Bulgarien',
              'en'   => 'Bulgaria',
              'filter' => ['EU'],
            ],
            self::BHR => [
              'de'   => 'Bahrain',
              'en'   => 'Bahrain',
            ],
            self::BDI => [
              'de'   => 'Burundi',
              'en'   => 'Burundi',
            ],
            self::BEN => [
              'de'   => 'Benin',
              'en'   => 'Benin',
            ],
            self::BLM => [
              'de'   => 'St. Barthélemy',
              'en'   => 'St. Barthélemy',
            ],
            self::BMU => [
              'de'   => 'Bermuda',
              'en'   => 'Bermuda',
            ],
            self::BRN => [
              'de'   => 'Brunei Darussalam',
              'en'   => 'Brunei Darussalam',
            ],
            self::BOL => [
              'de'   => 'Bolivien',
              'en'   => 'Bolivia',
            ],
            self::BES => [
              'de'   => 'Bonaire, Sint Eustatius und Saba',
              'en'   => 'Bonaire, Sint Eustatius and Saba',
            ],
            self::BRA => [
              'de'   => 'Brasilien',
              'en'   => 'Brazil',
            ],
            self::BHS => [
              'de'   => 'Bahamas',
              'en'   => 'Bahamas',
            ],
            self::BTN => [
              'de'   => 'Bhutan',
              'en'   => 'Bhutan',
            ],
            self::BVT => [
              'de'   => 'Bouvetinsel',
              'en'   => 'Bouvet Island',
            ],
            self::BWA => [
              'de'   => 'Botsuana',
              'en'   => 'Botswana',
            ],
            self::BLR => [
              'de'   => 'Weißrussland',
              'en'   => 'Belarus',
            ],
            self::BLZ => [
              'de'   => 'Belize',
              'en'   => 'Belize',
            ],
            self::CAN => [
              'de'   => 'Kanada',
              'en'   => 'Canada',
            ],
            self::CCK => [
              'de'   => 'Kokosinseln',
              'en'   => 'Cocos (Keeling) Islands',
            ],
            self::COD => [
              'de'   => 'Congo, the Democratic Republic of the',
              'en'   => 'Congo, the Democratic Republic of the',
            ],
            self::CAF => [
              'de'   => 'Zentralafrikanische Republik',
              'en'   => 'Central African Republic',
            ],
            self::COG => [
              'de'   => 'Kongo',
              'en'   => 'Congo',
            ],
            self::CHE => [
              'de'   => 'Schweiz',
              'en'   => 'Switzerland',
              'filter' => ['DACH'],
            ],
            self::CIV => [
              'de'   => 'Elfenbeinküste',
              'en'   => 'Cote D\'Ivoire',
            ],
            self::COK => [
              'de'   => 'Cookinseln',
              'en'   => 'Cook Islands',
            ],
            self::CHL => [
              'de'   => 'Chile',
              'en'   => 'Chile',
            ],
            self::CMR => [
              'de'   => 'Kamerun',
              'en'   => 'Cameroon',
            ],
            self::CHN => [
              'de'   => 'China',
              'en'   => 'China',
            ],
            self::COL => [
              'de'   => 'Kolumbien',
              'en'   => 'Colombia',
            ],
            self::CRI => [
              'de'   => 'Costa Rica',
              'en'   => 'Costa Rica',
            ],
            self::SCG => [
              'de'   => 'Serbia and Montenegro',
              'en'   => 'Serbia and Montenegro',
            ],
            self::CUB => [
              'de'   => 'Cuba',
              'en'   => 'Cuba',
            ],
            self::CPV => [
              'de'   => 'Cabo Verde',
              'en'   => 'Cape Verde',
            ],
            self::CUW => [
              'de'   => 'Curaçao',
              'en'   => 'Curaçao',
            ],
            self::CXR => [
              'de'   => 'Weihnachtsinsel',
              'en'   => 'Christmas Island',
            ],
            self::CYP => [
              'de'   => 'Zypern',
              'en'   => 'Cyprus',
              'filter' => ['EU'],
            ],
            self::CZE => [
              'de'   => 'Tschechische Republik',
              'en'   => 'Czech Republic',
              'filter' => ['EU'],
            ],
            self::DEU => [
              'de'   => 'Deutschland',
              'en'   => 'Germany',
              'filter' => ['EU', 'DACH'],
            ],
            self::DJI => [
              'de'   => 'Dschibuti',
              'en'   => 'Djibouti',
            ],
            self::DNK => [
              'de'   => 'Dänemark',
              'en'   => 'Denmark',
              'filter' => ['EU'],
            ],
            self::DMA => [
              'de'   => 'Dominica',
              'en'   => 'Dominica',
            ],
            self::DOM => [
              'de'   => 'Dominikanische Republik',
              'en'   => 'Dominican Republic',
            ],
            self::DZA => [
              'de'   => 'Algerien',
              'en'   => 'Algeria',
            ],
            self::ECU => [
              'de'   => 'Ecuador',
              'en'   => 'Ecuador',
            ],
            self::EST => [
              'de'   => 'Estland',
              'en'   => 'Estonia',
              'filter' => ['EU'],
            ],
            self::EGY => [
              'de'   => 'Ägypten',
              'en'   => 'Egypt',
            ],
            self::ESH => [
              'de'   => 'Westsahara',
              'en'   => 'Western Sahara',
            ],
            self::ERI => [
              'de'   => 'Eritrea',
              'en'   => 'Eritrea',
            ],
            self::ESP => [
              'de'   => 'Spanien',
              'en'   => 'Spain',
              'filter' => ['EU'],
            ],
            self::ETH => [
              'de'   => 'Äthiopien',
              'en'   => 'Ethiopia',
            ],
            self::FIN => [
              'de'   => 'Finnland',
              'en'   => 'Finland',
              'filter' => ['EU'],
            ],
            self::FJI => [
              'de'   => 'Fidschi',
              'en'   => 'Fiji',
            ],
            self::FLK => [
              'de'   => 'Falklandinseln',
              'en'   => 'Falkland Islands (Malvinas)',
            ],
            self::FSM => [
              'de'   => 'Mikronesien',
              'en'   => 'Micronesia, Federated States of',
            ],
            self::FRO => [
              'de'   => 'Färöer',
              'en'   => 'Faroe Islands',
            ],
            self::FRA => [
              'de'   => 'Frankreich',
              'en'   => 'France',
              'filter' => ['EU'],
            ],
            self::GAB => [
              'de'   => 'Gabun',
              'en'   => 'Gabon',
            ],
            self::GBR => [
              'de'   => 'Großbritannien',
              'en'   => 'United Kingdom',
              'filter' => ['EU'],
            ],
            self::GRD => [
              'de'   => 'Grenada',
              'en'   => 'Grenada',
            ],
            self::GEO => [
              'de'   => 'Georgien',
              'en'   => 'Georgia',
            ],
            self::GUF => [
              'de'   => 'Französisch-Guayana',
              'en'   => 'French Guiana',
            ],
            self::GGY => [
              'de'   => 'Guernsey',
              'en'   => 'Guernsey',
            ],
            self::GHA => [
              'de'   => 'Ghana',
              'en'   => 'Ghana',
            ],
            self::GIB => [
              'de'   => 'Gibraltar',
              'en'   => 'Gibraltar',
            ],
            self::GRL => [
              'de'   => 'Grönland',
              'en'   => 'Greenland',
            ],
            self::GMB => [
              'de'   => 'Gambia',
              'en'   => 'Gambia',
            ],
            self::GIN => [
              'de'   => 'Guinea',
              'en'   => 'Guinea',
            ],
            self::GLP => [
              'de'   => 'Guadeloupe',
              'en'   => 'Guadeloupe',
            ],
            self::GNQ => [
              'de'   => 'Äquatorialguinea',
              'en'   => 'Equatorial Guinea',
            ],
            self::GRC => [
              'de'   => 'Griechenland',
              'en'   => 'Greece',
              'filter' => ['EU'],
            ],
            self::SGS => [
              'de'   => 'Südgeorgien/Südlichen Sandwichinseln',
              'en'   => 'South Georgia and the South Sandwich Islands',
            ],
            self::GTM => [
              'de'   => 'Guatemala',
              'en'   => 'Guatemala',
            ],
            self::GUM => [
              'de'   => 'Guam',
              'en'   => 'Guam',
            ],
            self::GNB => [
              'de'   => 'Guinea-Bissau',
              'en'   => 'Guinea-Bissau',
            ],
            self::GUY => [
              'de'   => 'Guyana',
              'en'   => 'Guyana',
            ],
            self::HKG => [
              'de'   => 'Hong Kong',
              'en'   => 'Hong Kong',
            ],
            self::HMD => [
              'de'   => 'Heard und McDonaldinseln',
              'en'   => 'Heard Island and Mcdonald Islands',
            ],
            self::HND => [
              'de'   => 'Honduras',
              'en'   => 'Honduras',
            ],
            self::HRV => [
              'de'   => 'Kroatien',
              'en'   => 'Croatia',
              'filter' => ['EU'],
            ],
            self::HTI => [
              'de'   => 'Haiti',
              'en'   => 'Haiti',
            ],
            self::HUN => [
              'de'   => 'Ungarn',
              'en'   => 'Hungary',
              'filter' => ['EU'],
            ],
            self::IDN => [
              'de'   => 'Indonesien',
              'en'   => 'Indonesia',
            ],
            self::IRL => [
              'de'   => 'Irland',
              'en'   => 'Ireland',
              'filter' => ['EU'],
            ],
            self::ISR => [
              'de'   => 'Israel',
              'en'   => 'Israel',
            ],
            self::IMN => [
              'de'   => 'Isle Of Man',
              'en'   => 'Isle Of Man',
            ],
            self::IND => [
              'de'   => 'Indien',
              'en'   => 'India',
            ],
            self::IOT => [
              'de'   => 'British Indian Ocean Territory',
              'en'   => 'British Indian Ocean Territory',
            ],
            self::IRQ => [
              'de'   => 'Irak',
              'en'   => 'Iraq',
            ],
            self::IRN => [
              'de'   => 'Iran',
              'en'   => 'Iran, Islamic Republic of',
            ],
            self::ISL => [
              'de'   => 'Island',
              'en'   => 'Iceland',
            ],
            self::ITA => [
              'de'   => 'Italien',
              'en'   => 'Italy',
              'filter' => ['EU'],
            ],
            self::JEY => [
              'de'   => 'Jersey',
              'en'   => 'Jersey',
            ],
            self::JAM => [
              'de'   => 'Jamaika',
              'en'   => 'Jamaica',
            ],
            self::JOR => [
              'de'   => 'Jordanien',
              'en'   => 'Jordan',
            ],
            self::JPN => [
              'de'   => 'Japan',
              'en'   => 'Japan',
            ],
            self::KEN => [
              'de'   => 'Kenia',
              'en'   => 'Kenya',
            ],
            self::KGZ => [
              'de'   => 'Kirgisistan',
              'en'   => 'Kyrgyzstan',
            ],
            self::KHM => [
              'de'   => 'Kambodscha',
              'en'   => 'Cambodia',
            ],
            self::KIR => [
              'de'   => 'Kiribati',
              'en'   => 'Kiribati',
            ],
            self::COM => [
              'de'   => 'Komoren',
              'en'   => 'Comoros',
            ],
            self::KNA => [
              'de'   => 'St. Kitts/Nevis',
              'en'   => 'Saint Kitts and Nevis',
            ],
            self::PRK => [
              'de'   => 'Nordkorea',
              'en'   => 'Korea, Democratic People\'s Republic of',
            ],
            self::KOR => [
              'de'   => 'Südkorea',
              'en'   => 'Korea, Republic of',
            ],
            self::KWT => [
              'de'   => 'Kuwait',
              'en'   => 'Kuwait',
            ],
            self::CYM => [
              'de'   => 'Kaimaninseln',
              'en'   => 'Cayman Islands',
            ],
            self::KAZ => [
              'de'   => 'Kasachstan',
              'en'   => 'Kazakhstan',
            ],
            self::LAO => [
              'de'   => 'Laos',
              'en'   => 'Lao People\'s Democratic Republic',
            ],
            self::LBN => [
              'de'   => 'Libanon',
              'en'   => 'Lebanon',
            ],
            self::LCA => [
              'de'   => 'St. Lucia',
              'en'   => 'Saint Lucia',
            ],
            self::LIE => [
              'de'   => 'Liechtenstein',
              'en'   => 'Liechtenstein',
            ],
            self::LKA => [
              'de'   => 'Sri Lanka',
              'en'   => 'Sri Lanka',
            ],
            self::LBR => [
              'de'   => 'Liberia',
              'en'   => 'Liberia',
            ],
            self::LSO => [
              'de'   => 'Lesotho',
              'en'   => 'Lesotho',
            ],
            self::LTU => [
              'de'   => 'Litauen',
              'en'   => 'Lithuania',
              'filter' => ['EU'],
            ],
            self::LUX => [
              'de'   => 'Luxemburg',
              'en'   => 'Luxembourg',
              'filter' => ['EU', 'BX'],
            ],
            self::LVA => [
              'de'   => 'Lettland',
              'en'   => 'Latvia',
              'filter' => ['EU'],
            ],
            self::LBY => [
              'de'   => 'Libyen',
              'en'   => 'Libyan Arab Jamahiriya',
            ],
            self::MAR => [
              'de'   => 'Morokko',
              'en'   => 'Morocco',
            ],
            self::MCO => [
              'de'   => 'Monaco',
              'en'   => 'Monaco',
            ],
            self::MDA => [
              'de'   => 'Moldawien',
              'en'   => 'Moldova, Republic of',
            ],
            self::MNE => [
              'de'   => 'Montenegro',
              'en'   => 'Montenegro',
            ],
            self::MAF => [
              'de'   => 'St. Martin',
              'en'   => 'St. Martin',
            ],
            self::MDG => [
              'de'   => 'Madagaskar',
              'en'   => 'Madagascar',
            ],
            self::MHL => [
              'de'   => 'Marshallinseln',
              'en'   => 'Marshall Islands',
            ],
            self::MKD => [
              'de'   => 'Mazedonien',
              'en'   => 'Macedonia, the Former Yugoslav Republic of',
            ],
            self::MLI => [
              'de'   => 'Mali',
              'en'   => 'Mali',
            ],
            self::MMR => [
              'de'   => 'Myanmar',
              'en'   => 'Myanmar',
            ],
            self::MNG => [
              'de'   => 'Mongolei',
              'en'   => 'Mongolia',
            ],
            self::MAC => [
              'de'   => 'Macao',
              'en'   => 'Macao',
            ],
            self::MNP => [
              'de'   => 'Nördliche Marianen',
              'en'   => 'Northern Mariana Islands',
            ],
            self::MTQ => [
              'de'   => 'Martinique',
              'en'   => 'Martinique',
            ],
            self::MRT => [
              'de'   => 'Mauretanien',
              'en'   => 'Mauritania',
            ],
            self::MSR => [
              'de'   => 'Montserrat',
              'en'   => 'Montserrat',
            ],
            self::MLT => [
              'de'   => 'Malta',
              'en'   => 'Malta',
              'filter' => ['EU'],
            ],
            self::MUS => [
              'de'   => 'Mauritius',
              'en'   => 'Mauritius',
            ],
            self::MDV => [
              'de'   => 'Maldiven',
              'en'   => 'Maldives',
            ],
            self::MWI => [
              'de'   => 'Malawi',
              'en'   => 'Malawi',
            ],
            self::MEX => [
              'de'   => 'Mexiko',
              'en'   => 'Mexico',
            ],
            self::MYS => [
              'de'   => 'Malaysia',
              'en'   => 'Malaysia',
            ],
            self::MOZ => [
              'de'   => 'Mosambik',
              'en'   => 'Mozambique',
            ],
            self::NAM => [
              'de'   => 'Namibia',
              'en'   => 'Namibia',
            ],
            self::NCL => [
              'de'   => 'Neukaledonien',
              'en'   => 'New Caledonia',
            ],
            self::NER => [
              'de'   => 'Niger',
              'en'   => 'Niger',
            ],
            self::NFK => [
              'de'   => 'Norfolkinsel',
              'en'   => 'Norfolk Island',
            ],
            self::NGA => [
              'de'   => 'Nigeria',
              'en'   => 'Nigeria',
            ],
            self::NIC => [
              'de'   => 'Nicaragua',
              'en'   => 'Nicaragua',
            ],
            self::NLD => [
              'de'   => 'Niederlande',
              'en'   => 'Netherlands',
              'filter' => ['EU', 'BX'],
            ],
            self::NOR => [
              'de'   => 'Norwegen',
              'en'   => 'Norway',
            ],
            self::NPL => [
              'de'   => 'Nepal',
              'en'   => 'Nepal',
            ],
            self::NRU => [
              'de'   => 'Nauru',
              'en'   => 'Nauru',
            ],
            self::NIU => [
              'de'   => 'Niue',
              'en'   => 'Niue',
            ],
            self::NZL => [
              'de'   => 'Neuseeland',
              'en'   => 'New Zealand',
            ],
            self::OMN => [
              'de'   => 'Oman',
              'en'   => 'Oman',
            ],
            self::PAN => [
              'de'   => 'Panama',
              'en'   => 'Panama',
            ],
            self::PER => [
              'de'   => 'Peru',
              'en'   => 'Peru',
            ],
            self::PYF => [
              'de'   => 'Französisch-Polynesien',
              'en'   => 'French Polynesia',
            ],
            self::PNG => [
              'de'   => 'Papua-Neuguinea',
              'en'   => 'Papua New Guinea',
            ],
            self::PHL => [
              'de'   => 'Philippinen',
              'en'   => 'Philippines',
            ],
            self::PAK => [
              'de'   => 'Pakistan',
              'en'   => 'Pakistan',
            ],
            self::POL => [
              'de'   => 'Polen',
              'en'   => 'Poland',
              'filter' => ['EU'],
            ],
            self::SPM => [
              'de'   => 'St. Pierre/Miquelon',
              'en'   => 'Saint Pierre and Miquelon',
            ],
            self::PCN => [
              'de'   => 'Pitcairninseln',
              'en'   => 'Pitcairn',
            ],
            self::PRI => [
              'de'   => 'Puerto Rico',
              'en'   => 'Puerto Rico',
            ],
            self::PSE => [
              'de'   => 'Palestina',
              'en'   => 'Palestinian Territory, Occupied',
            ],
            self::PRT => [
              'de'   => 'Portugal',
              'en'   => 'Portugal',
              'filter' => ['EU'],
            ],
            self::PLW => [
              'de'   => 'Palau',
              'en'   => 'Palau',
            ],
            self::PRY => [
              'de'   => 'Paraguay',
              'en'   => 'Paraguay',
            ],
            self::QAT => [
              'de'   => 'Qatar',
              'en'   => 'Qatar',
            ],
            self::REU => [
              'de'   => 'Réunion',
              'en'   => 'Reunion',
            ],
            self::ROU => [
              'de'   => 'Rumänien',
              'en'   => 'Romania',
              'filter' => ['EU'],
            ],
            self::SRB => [
              'de'   => 'Serbien',
              'en'   => 'Serbien',
            ],
            self::RUS => [
              'de'   => 'Russland',
              'en'   => 'Russian Federation',
            ],
            self::RWA => [
              'de'   => 'Ruanda',
              'en'   => 'Rwanda',
            ],
            self::SAU => [
              'de'   => 'Saudi-Arabien',
              'en'   => 'Saudi Arabia',
            ],
            self::SLB => [
              'de'   => 'Salomonen',
              'en'   => 'Solomon Islands',
            ],
            self::SYC => [
              'de'   => 'Seychellen',
              'en'   => 'Seychelles',
            ],
            self::SDN => [
              'de'   => 'Sudan',
              'en'   => 'Sudan',
            ],
            self::SWE => [
              'de'   => 'Schweden',
              'en'   => 'Sweden',
              'filter' => ['EU'],
            ],
            self::SGP => [
              'de'   => 'Singapur',
              'en'   => 'Singapore',
            ],
            self::SHN => [
              'de'   => 'St. Helena',
              'en'   => 'Saint Helena',
            ],
            self::SVN => [
              'de'   => 'Slowenien',
              'en'   => 'Slovenia',
              'filter' => ['EU'],
            ],
            self::SJM => [
              'de'   => 'Svalbard/Jan Mayen',
              'en'   => 'Svalbard and Jan Mayen',
            ],
            self::SVK => [
              'de'   => 'Slowakische Republik',
              'en'   => 'Slovakia',
              'filter' => ['EU'],
            ],
            self::SLE => [
              'de'   => 'Sierra Leone',
              'en'   => 'Sierra Leone',
            ],
            self::SMR => [
              'de'   => 'San Marino',
              'en'   => 'San Marino',
            ],
            self::SEN => [
              'de'   => 'Senegal',
              'en'   => 'Senegal',
            ],
            self::SOM => [
              'de'   => 'Somalia',
              'en'   => 'Somalia',
            ],
            self::SUR => [
              'de'   => 'Surinam',
              'en'   => 'Suriname',
            ],
            self::STP => [
              'de'   => 'São Tomé/Príncipe',
              'en'   => 'Sao Tome and Principe',
            ],
            self::SLV => [
              'de'   => 'El Salvador',
              'en'   => 'El Salvador',
            ],
            self::SXM => [
              'de'   => 'Sint Maarten',
              'en'   => 'Sint Maarten',
            ],
            self::SYR => [
              'de'   => 'Syrien',
              'en'   => 'Syrian Arab Republic',
            ],
            self::SWZ => [
              'de'   => 'Swasiland',
              'en'   => 'Swaziland',
            ],
            self::TCA => [
              'de'   => 'Turks- und Caicosinseln',
              'en'   => 'Turks and Caicos Islands',
            ],
            self::TCD => [
              'de'   => 'Tschad',
              'en'   => 'Chad',
            ],
            self::ATF => [
              'de'   => 'Französische Südpolarterritorien',
              'en'   => 'French Southern Territories',
            ],
            self::TGO => [
              'de'   => 'Togo',
              'en'   => 'Togo',
            ],
            self::THA => [
              'de'   => 'Thailand',
              'en'   => 'Thailand',
            ],
            self::TJK => [
              'de'   => 'Tadschikistan',
              'en'   => 'Tajikistan',
            ],
            self::TKL => [
              'de'   => 'Tokelau',
              'en'   => 'Tokelau',
            ],
            self::TLS => [
              'de'   => 'Timor-Leste',
              'en'   => 'Timor-Leste',
            ],
            self::TKM => [
              'de'   => 'Turkmenistan',
              'en'   => 'Turkmenistan',
            ],
            self::TUN => [
              'de'   => 'Tunisien',
              'en'   => 'Tunisia',
            ],
            self::TON => [
              'de'   => 'Tonga',
              'en'   => 'Tonga',
            ],
            self::TUR => [
              'de'   => 'Türkei',
              'en'   => 'Turkey',
            ],
            self::TTO => [
              'de'   => 'Trinidad und Tobago',
              'en'   => 'Trinidad and Tobago',
            ],
            self::TUV => [
              'de'   => 'Tuvalu',
              'en'   => 'Tuvalu',
            ],
            self::TWN => [
              'de'   => 'Taiwan',
              'en'   => 'Taiwan, Province of China',
            ],
            self::TZA => [
              'de'   => 'Tansania',
              'en'   => 'Tanzania, United Republic of',
            ],
            self::UKR => [
              'de'   => 'Ukraine',
              'en'   => 'Ukraine',
            ],
            self::UGA => [
              'de'   => 'Uganda',
              'en'   => 'Uganda',
            ],
            self::UMI => [
              'de'   => 'United States Minor Islands',
              'en'   => 'United States Minor Outlying Islands',
            ],
            self::USA => [
              'de'   => 'USA',
              'en'   => 'United States',
            ],
            self::URY => [
              'de'   => 'Uruguay',
              'en'   => 'Uruguay',
            ],
            self::UZB => [
              'de'   => 'Usbekistan',
              'en'   => 'Uzbekistan',
            ],
            self::VAT => [
              'de'   => 'Vatikanstadt',
              'en'   => 'Holy See (Vatican City State)',
            ],
            self::VCT => [
              'de'   => 'St. Vincent/Die Grenadinen',
              'en'   => 'Saint Vincent and the Grenadines',
            ],
            self::VEN => [
              'de'   => 'Venezuela',
              'en'   => 'Venezuela',
            ],
            self::VGB => [
              'de'   => 'Jungferninseln, Britische',
              'en'   => 'Virgin Islands, British',
            ],
            self::VIR => [
              'de'   => 'Jungferninseln, Amerikanische',
              'en'   => 'Virgin Islands, U.s.',
            ],
            self::VNM => [
              'de'   => 'Vietnam',
              'en'   => 'Viet Nam',
            ],
            self::VUT => [
              'de'   => 'Vanuatu',
              'en'   => 'Vanuatu',
            ],
            self::WLF => [
              'de'   => 'Wallis/Futuna',
              'en'   => 'Wallis and Futuna',
            ],
            self::WSM => [
              'de'   => 'Samoa',
              'en'   => 'Samoa',
            ],
            self::XKX => [
              'de'   => 'Kosovo',
              'en'   => 'Kosovo',
            ],
            self::YEM => [
              'de'   => 'Jemen',
              'en'   => 'Yemen',
            ],
            self::MYT => [
              'de'   => 'Mayotte',
              'en'   => 'Mayotte',
            ],
            self::ZAF => [
              'de'   => 'Südafrika',
              'en'   => 'South Africa',
            ],
            self::ZMB => [
              'de'   => 'Sambia',
              'en'   => 'Zambia',
            ],
            self::ZWE => [
              'de'   => 'Simbabwe',
              'en'   => 'Zimbabwe',
            ],
        };

        $match['iso2'] = $this->value;
        $match['iso3'] = $this->name;

        $locales = [
          'de' => 'de_DE.UTF-8',
          'en' => 'en_US.UTF-8',
        ];

        foreach ($locales as $localeShort => $localeLong) {
            $localeBackup = setlocale(LC_CTYPE, 0);
            setlocale(LC_CTYPE, $localeLong);

            $value = str_replace(['Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'ß'], ['Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue', 'ss'], $match[$localeShort] ?? '');
            $match[$localeShort.'_transliterated'] = iconv('UTF-8', 'ASCII//TRANSLIT', $value);

            setlocale(LC_CTYPE, $localeBackup);
        }

        return $match;
    }

    public function iso2(): string { return $this->value; }
    public function iso3(): string { return $this->name; }

    public function de(): ?string { return $this->field('de'); }
    public function en(): ?string { return $this->field('en'); }

    /*
    public static function tryFromIso(?string $iso2orIso3): ?self
    {
        if ($iso2orIso3 === null) {
            return null;
        }
        $iso2orIso3 = strtoupper(trim($iso2orIso3));
        if (strlen($iso2orIso3) === 3) {
            try {
                return self::{$iso2orIso3};
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
            return self::{$iso2orIso3};
        }

        return self::from($iso2orIso3);
    }
    */

}
