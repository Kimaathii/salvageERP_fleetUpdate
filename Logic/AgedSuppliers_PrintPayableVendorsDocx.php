<?php

namespace Logic;

/** @array $vendors */

use PhpOffice\PhpWord;
use PhpOffice\PhpWord\{
    SimpleType\Jc as HorizontalAlign,
};
use PhpOffice\PhpWord\Element\{
    Section,
    Table
};

// $PageSecurity = false;
// require_once __DIR__ . '/includes/session.php';

/**
 * The total amount to be paid
 */
$amount = $vendors['total'];

$phpword = new PhpWord\PhpWord;
$section = $phpword->addSection();
$section->addText();
$section->addTextBreak();
insertSectionText($section,
    date('jS F, Y.') . "\n\n"
    . "The Manager,\n"
    . "Stanbic IBTC Bank Plc,\n"
    . "Ahmadu Bello Way,\n"
    . "Kaduna.\n\n"
    . "Dear Sir,\n"
);

$section->addText("ELECTRONIC PAYMENT", ['bold' => true, ], ['alignment' => HorizontalAlign::CENTER]);
$textRun = $section->addTextRun();
$textRun->addText("PAYEE: ", ['bold' => true, ]);
$textRun->addText('Various');
$textRun = $section->addTextRun();
$textRun->addText("AMOUNT:", ['bold' => true, ]);
$textRun->addText(' &#8358;' . number_format($amount, 2));
$section->addTextBreak();

$textRun = $section->addTextRun();
$textRun->addText('We hereby authorize you to pay electronically from our ');
$textRun->addText("#INSERT_COMPANY_NAME# ACCOUNT NUMBER #INSERT_ACCOUNT_NUMBER#", ['bold' => true, ]);
$textRun->addText(', a total sum of ');
$textRun->addText(sprintf('&#8358;%s', number_format($amount, 2)), ['bold' => true]);
$textRun->addText(sprintf(' (%s naira) only, to the various underlisted beneficiaries:', number_to_word($amount)));
// $textRun->addText(sprintf(' (%s naira) only, to the various underlisted beneficiaries.', numfmt_create('en_GB', NumberFormatter::SPELLOUT)->format($amount)));

$section->addTextBreak();

createTableFromArray(
    $section->addTable(['borderColor' => '#000', 'borderSize' => 1, 'cellMargin' => 50]),
    array_merge(
        [
            // Table Headers
            array_map(
                function($v) { return ['text' => $v, 'fStyle' => ['bold' => true]]; },
                ['#', 'ACCOUNT NAME', 'BANK', 'ACCOUNT NUMBER', 'AMOUNT']
            ),
        ],
        array_map(
            function(array $vendor) {
                static $idx = 0;
                return [++$idx, $vendor['name'], $vendor['bankName'], $vendor['accountNumber'], '&#8358;' . number_format($vendor['balance'])];
            },
            $vendors['rows']
        )
    )
);

outputDocFile($phpword);
exit;

function outputDocFile(PhpWord\PhpWord $phpword) {
    header("Content-Description: File Transfer");
    header(sprintf('Content-Disposition: attachment; filename="%s.docx"', $_SESSION['DatabaseName'] . '_PayableVendors_' . date('Y-m-d')));
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpword, 'Word2007');
    $xmlWriter->save("php://output");
}

function insertSectionText(Section $section, string $text) {
    $lines = explode("\n", $text);
    foreach ($lines as $line) {
        $section->addText($line);
    }
}

function createTableFromArray(Table $table, array $array) {
    foreach ($array as $row) {
        addTableRow($table, $row, null);
    }
}

/**
 * @param string|array{'text': string, 'fStyle': ?array, 'pStyle': ?array, 'span': ?number}[] $columns An array of 
 * */
function addTableRow(Table $table, array $columns, ?array $rowStyle = []) {
    $table->addRow(null, $rowStyle);
    foreach ($columns as $cellData) {
        $cell = $table->addCell(null, @$cellData['cellStyle']);
        if (is_array($cellData)) {
            $cell->addText($cellData['text'], @$cellData['fStyle'], @$cellData['pStyle']);
            $cell->getStyle()->setGridSpan($cellData['span'] ?? 1);
        } else {
            $cell->addText($cellData);
        }
    }
}

/**
 * @author https://pageconfig.com/post/number-to-word-conversion-with-php
 */
function number_to_word( $num = '' )
{
    $num    = ( string ) ( ( int ) $num );

    if( ( int ) ( $num ) && ctype_digit( $num ) )
    {
        $words  = array( );

        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );

        $list1  = array('','one','two','three','four','five','six','seven',
            'eight','nine','ten','eleven','twelve','thirteen','fourteen',
            'fifteen','sixteen','seventeen','eighteen','nineteen');

        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
            'seventy','eighty','ninety','hundred');

        $list3  = array('','thousand','million','billion','trillion',
            'quadrillion','quintillion','sextillion','septillion',
            'octillion','nonillion','decillion','undecillion',
            'duodecillion','tredecillion','quattuordecillion',
            'quindecillion','sexdecillion','septendecillion',
            'octodecillion','novemdecillion','vigintillion');

        $num_length = strlen( $num );
        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
        $max_length = $levels * 3;
        $num    = substr( '00'.$num , -$max_length );
        $num_levels = str_split( $num , 3 );

        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';

            if( $tens < 20 ) {
                $tens = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = ( int ) ( $tens / 10 ); $tens = ' ' . $list2[$tens] . ' '; $singles = ( int ) ( $num_part % 10 ); $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        }
        $commas = count( $words );
        if( $commas > 1 )
        {
            $commas = $commas - 1;
        }

        $words  = implode( ', ' , $words );

        //Some Finishing Touch
        //Replacing multiples of spaces with one space
        $words  = trim( str_replace( ' ,' , ',' , trim_all( ucwords( $words ) ) ) , ', ' );
        // if( $commas )
        // {
        //     $words  = str_replace_last( ',' , ' and' , $words );
        // }

        return $words;
    }
    else if( ! ( ( int ) $num ) )
    {
        return 'Zero';
    }
    return '';
}


/**
 * @author https://pageconfig.com/post/number-to-word-conversion-with-php
 */
function trim_all( $str , $what = NULL , $with = ' ' )
{
    if( $what === NULL )
    {
        //  Character      Decimal      Use
        //  "\0"            0           Null Character
        //  "\t"            9           Tab
        //  "\n"           10           New line
        //  "\x0B"         11           Vertical Tab
        //  "\r"           13           New Line in Mac
        //  " "            32           Space
       
        $what   = "\\x00-\\x20";    //all white-spaces and control chars
    }
   
    return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
}

/**
 * @author https://pageconfig.com/post/number-to-word-conversion-with-php
 */
function str_replace_last( $search , $replace , $str ) {
    if( ( $pos = strrpos( $str , $search ) ) !== false ) {
        $search_length  = strlen( $search );
        $str    = substr_replace( $str , $replace , $pos , $search_length );
    }
    return $str;
}

