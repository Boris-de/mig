<?php // $Revision$

//
// Mig - A general purpose photo gallery management system.
//
// Copyright 2000-2002 Daniel M. Lowe <dan@tangledhelix.com>
//
// http://mig.sourceforge.net/
//

// The release number we are running.
$version = '1.3.2b1';


//
// LICENSE INFORMATION
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// You can find a copy of the GPL online at:
// http://www.gnu.org/copyleft/gpl.html
//
// END OF LICENSE INFORMATION
//


//
// Please see the files in the docs subdirectory.
//
// Do not modify this file directly.  Please see the file docs/INSTALL
// or docs/html/Install.html for installation directions.  Most things
// you'd want to customize can be customized outside of this file.
//
// If you find that is not the case, and you hack in support for some
// feature you want to see in Mig, please contact me with a code diff and
// if I agree that it is useful to the general public, I will incorporate
// your code into the main code base for distribution.  (For the record,
// I prefer contextual diffs, i.e. "diff -c".)
//
// If I don't incorporate it I may very well offer it as "contributed"
// code that others can download if they wish to do so.
//


// Defaults - probably over-ridden by config.php
//

$maxFolderColumns       = 2;
$maxThumbColumns        = 4;
$pageTitle              = 'My Photo Album';
$maintAddr              = 'webmaster@mydomain.com';
$distURL                = 'http://mig.sourceforge.net/';
$markerType             = 'suffix';
$markerLabel            = 'th';
$phpNukeCompatible      = FALSE;
$suppressImageInfo      = FALSE;
$useThumbSubdir         = TRUE;
$thumbSubdir            = 'thumbs';
$noThumbs               = FALSE;
$suppressAltTags        = FALSE;
$mig_language           = 'en';
$sortType               = 'default';
$viewCamInfo            = FALSE;
$viewDateInfo           = FALSE;
$viewFolderCount        = FALSE;
$imagePopup             = FALSE;
$imagePopType           = 'reuse';
$commentFilePerImage    = FALSE;


//
// LANGUAGE TRANSLATIONS
//
//  en      English     (Default)
//  fr      French
//  de      German
//  no      Norwegian
//  br      Portugese
//  fi      Finnish
//  ro      Romanian
//  ru      Russian Windows-1251
//  koi8r   Russian KOI8-R
//  tr      Turkish
//  se      Swedish
//  da      Danish
//  it      Italian
//  es      Spanish
//  sk      Slovak
//  nl      Dutch
//  pl      Polish
//

// English (default)
$mig_messages['en'] = array (
    'backhome'      => 'back&nbsp;to',
    'bytes'         => '&nbsp;bytes',
    'flash_used'    => 'flash used',
    'main'          => 'Main',
    'must_auth'     => 'You must enter a valid username and password to'
                     . ' enter',
    'nextimage'     => 'next&nbsp;image',
    'no_contents'   => 'No&nbsp;contents.',
    'previmage'     => 'previous&nbsp;image',
    'thumbview'     => 'back&nbsp;to&nbsp;thumbnail&nbsp;view',
    'up_one'        => 'up&nbsp;one&nbsp;level',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// French - courtesy of jérôme ROUER <CCFRANCE.bibli@bigpond.com.kh>
$mig_messages['fr'] = array (
    'backhome'      => 'Retour&nbsp;vers',
    'bytes'         => '&nbsp;octets',
    'flash_used'    => 'flash utilis&#233;',
    'main'          => 'Liste&nbsp;des&nbsp;albums&nbsp;par&nbsp;classements'
                     . '&nbsp;(r&#233;pertoires)',
    'must_auth'     => 'Vous devez entrer un username et un mot de passe'
                     . ' valides pour entrer',
    'nextimage'     => 'Image&nbsp;suivante',
    'no_contents'   => 'VIDE',
    'previmage'     => 'Image&nbsp;pr&#233;c&#233;dente',
    'thumbview'     => 'Retour&nbsp;vue&nbsp;par&nbsp;vignettes',
    'up_one'        => 'Remonter&nbsp;niveau&nbsp;sup&#233;rieur',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// German - courtesy of "Burckhard Loeh" <lb@loeh.cx>
$mig_messages['de'] = array (
    'backhome'      => 'zurueck&nbsp;zu',
    'bytes'         => '&nbsp;bytes',
    'flash_used'    => 'blitz benutzt',
    'main'          => 'Hauptverzeichnis',
    'must_auth'     => 'Fuer den Zugang muessen Sie eine gueltige'
                     . ' Benutzerkennung und Passwort eingeben',
    'nextimage'     => 'naechstes&nbsp;Bild',
    'no_contents'   => 'Keinen&nbsp;Inhalt.',
    'previmage'     => 'vorheriges&nbsp;Bild',
    'thumbview'     => 'zurueck&nbsp;zur&nbsp;Kleinbild&nbsp;uebersicht',
    'up_one'        => 'Eine&nbsp;Ebene&nbsp;hoeher',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Norwegian
// Translated by Joffer aka Christopher Thorjussen - January 8th, 2000
// http://www.irc-arendal.net
$mig_messages['no'] = array (
    'backhome'      => 'tilbake',
    'bytes'         => '&nbsp;bytes',
    'flash_used'    => 'flash used',
    'main'          => 'Galleriet',
    'must_auth'     => 'Du m&#229; oppgi gyldig brukernavn og passord',
    'nextimage'     => 'neste&nbsp;bilde',
    'no_contents'   => 'Tom&nbsp;katalog',
    'previmage'     => 'forrige&nbsp;bilde',
    'thumbview'     => 'tilbake&nbsp;til&nbsp;oversikt',
    'up_one'        => 'opp&nbsp;et&nbsp;niv&#229;',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Portugese - courtesy of Fadel <fadel@fee.unicamp.br>
$mig_messages['br'] = array (
    'backhome'      => 'voltar&nbsp;para',
    'bytes'         => '&nbsp;bytes',
    'flash_used'    => 'flash usado',
    'main'          => 'Principal',
    'must_auth'     => 'Voc&#234; deve entrar com um login e senha'
                     . ' v&#225;lido para acessar este conte&#250;do',
    'nextimage'     => 'pr&#243;xima&nbsp;imagem',
    'no_contents'   => 'Sem&nbsp;conte&#250;do.',
    'previmage'     => 'imagem&nbsp;anterior',
    'thumbview'     => 'Voltar&nbsp;para&nbsp;os&nbsp;thumbnails',
    'up_one'        => 'subir&nbsp;um&nbsp;n&#237;vel',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Finnish - courtesy of Jani Mikkonen <jani@mikkonen.org>
$mig_messages['fi'] = array (
    'backhome'      => 'takaisin',
    'bytes'         => '&nbsp;byte&#228;',
    'flash_used'    => 'flash used',
    'main'          => 'Etusivu',
    'must_auth'     => 'Sinun pit&#228;&#228; antaa oikea tunnus ja salasana'
                     . ' jatkaasesi eteenp &#228;in',
    'nextimage'     => 'seuraava&nbsp;kuva',
    'no_contents'   => 'Albumi&nbsp;on&nbsp;tyhj&#228;',
    'previmage'     => 'edellinen&nbsp;kuva',
    'thumbview'     => 'takaisin&nbsp;kuva&nbsp;valikkoon',
    'up_one'        => 'paluu&nbsp;edelliselle&nbsp;sivulle',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Romanian - courtesy of Eugen Dedu <dedu@ese-metz.fr>
$mig_messages['ro'] = array (
    'backhome'      => '&#238;napoi&nbsp;la',
    'bytes'         => '&nbsp;octeti',
    'flash_used'    => 'flash used',
    'main'          => 'Principal',
    'must_auth'     => 'Pentru a intra, trebuie sa introduceti un nume de'
                     . ' utilizator si o parola valide',
    'nextimage'     => 'imaginea&nbsp;urmatoare',
    'no_contents'   => 'Repertoriu&nbsp;GOL',
    'previmage'     => 'imaginea&nbsp;precedenta',
    'thumbview'     => '&#238;napoi&nbsp;la&nbsp;repertoriu',
    'up_one'        => 'nivelul&nbsp;precedent',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);


// Russian Windows-1251 - courtesy of Anatoly Ropotov <lynx@rulez.lv>
$mig_messages['ru'] = array (
    'backhome'      => '&#226;&229;&#240;&#237;&#243;&#242;&#252;&#241;'
                     . '&#255;&nbsp;&#234;',
    'bytes'         => '&nbsp;&#225;&#224;&#233;&#242;',
    'flash_used'    => 'flash used',
    'main'          => '&#203;&#232;&#246;&#229;&#226;&#224;&#255;',
    'must_auth'     => '&#194;&#251; &#228;&#238;&#235;&#230;&#237;&#251;'
                     . ' &#226;&#226;&#229;&#241;&#242;&#232; &#234;&#238;'
                     . '&#240;&#240;&#229;&#234;&#242;&#237;&#238;&#229;'
                     . ' &#232;&#236;&#255; &#239;&#238;&#235;&#252;&#231;'
                     . '&#238;&#226;&#224;&#242;&#229;&#235;&#255; &#232;'
                     . ' &#239;&#224;&#240;&#238;&#235;&#252;',
    'nextimage'     => '&#241;&#235;&#229;&#228;&#243;&#254;&#249;&#224;'
                     . '&#255;&nbsp;&#234;&#224;&#240;&#242;&#232;&#237;'
                     . '&#234;&#224;',
    'no_contents'   => '&#207;&#243;&#241;&#242;&#238;.',
    'previmage'     => '&#239;&#240;&#229;&#228;&#251;&#228;&#243;&#249;'
                     . '&#224;&#255;&nbsp;&#234;&#224;&#240;&#242;&#232;'
                     . '&#237;&#238;&#224;',
    'thumbview'     => '&#226;&#229;&#240;&#237;&#243;&#242;&#252;&#241;'
                     . '&#255;&nbsp;&#234;&nbsp;&#243;&#236;&#229;&#237;'
                     . '&#252;&#248;&#229;&#237;&#237;&#238;&#236;&#243;'
                     . '&nbsp;&#226;&#232;&#228;&#243;',
    'up_one'        => '&#239;&#240;&#229;&#228;&#251;&#228;&#243;&#249;'
                     . '&#224;&#255;&nbsp;&#239;&#224;&#239;&#234;&#224;',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Russian KOI8-R - courtesy of Anatoly Ropotov <lynx@rulez.lv>
$mig_messages['koi8r'] = array (
    'backhome'      => '&#215;&#197;&#210;&#206;&#213;&#212;&#216;&#211;'
                     . '&#209;&nbsp;&#203;',
    'bytes'         => '&nbsp;&#194;&#193;&#202;&#212;',
    'flash_used'    => 'flash used',
    'main'          => '&#236;&#201;&#195;&#197;&#215;&#193;&#209;',
    'must_auth'     => '&#247;&#217; &#196;&#207;&#204;&#214;&#206;&#217;'
                     . ' &#215;&#215;&#197;&#211;&#212;&#201; &#203;&#207;'
                     . '&#210;&#210;&#197;&#203;&#212;&#206;&#207;&#197;'
                     . ' &#201;&#205;&#209; &#208;&#207;&#204;&#216;&#218;'
                     . '&#207;&#215;&#193;&#212;&#197;&#204;&#209; &#201;'
                     . ' &#208;&#193;&#210;&#207;&#204;&#216;',
    'nextimage'     => '&#211;&#204;&#197;&#196;&#213;&#193;&#221;&#193;'
                     . '&#209;&nbsp;&#203;&#193;&#210;&#212;&#201;&#206;'
                     . '&#203;&#193;',
    'no_contents'   => '&#240;&#213;&#211;&#212;&#207;.',
    'previmage'     => '&#208;&#210;&#197;&#196;&#217;&#196;&#213;&#221;'
                     . '&#193;&#209;&nbsp;&#203;&#193;&#210;&#212;&#201;'
                     . '&#206;&#203;&#193;',
    'thumbview'     => '&#215;&#197;&#210;&#206;&#213;&#212;&#216;&#211;'
                     . '&#209;&nbsp;&#203;&nbsp;&#213;&#205;&#197;&#206;'
                     . '&#216;&#219;&#197;&#206;&#206;&#207;&#205;&#213;'
                     . '&nbsp;&#215;&#201;&#196;&#213;',
    'up_one'        => '&#208;&#210;&#197;&#196;&#217;&#196;&#213;&#221;'
                     . '&#193;&#209;&nbsp;&#208;&#193;&#208;&#203;&#193;',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Turkish - courtesy of Dogan Sariguzel <dogan@procam.com.tr>
$mig_messages['tr'] = array (
    'backhome'      => 'geri&nbsp;d&#246;n:',
    'bytes'         => '&nbsp;bit',
    'flash_used'    => 'flash used',
    'main'          => 'Ana',
    'must_auth'     => 'Ge&#231;erli bir kullan&#253;c&#253; ad&#253; ve'
                     . ' &#254;ifre girmelisiniz.',
    'nextimage'     => 'sonraki&nbsp;resim',
    'no_contents'   => 'i&#231;erik&nbsp;yok.',
    'previmage'     => '&#246;nceki&nbsp;resim',
    'thumbview'     => 'k&#252;&#231;&#252;k&nbsp;resimlere&nbsp;geri&nbsp;'
                     . 'd&#246;n',
    'up_one'        => 'bir&nbsp;seviye&nbsp;yukar&#253;',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Swedish - courtesy of Sebastian Djupsjöbacka <basse@iki.fi>
$mig_messages['se'] = array (
    'backhome'      => 'tillbaka',
    'bytes'         => '&nbsp;byte',
    'flash_used'    => 'flash used',
    'main'          => 'F&ouml;rsta&nbsp;sidan',
    'must_auth'     => 'Du m&aring;ste ange korrekt anv&auml;ndarnamn och'
                     . ' l&ouml;senord f&ouml;r att komma vidare',
    'nextimage'     => 'n&auml;sta&nbsp;bild',
    'no_contents'   => 'Det&nbsp;h&auml;r&nbsp;albumet&nbsp;&auml;r'
                     . '&nbsp;tomt',
    'previmage'     => 'f&ouml;reg&aring;ende&nbsp;bild',
    'thumbview'     => 'tillbaka&nbsp;till&nbsp;miniatyrbilderna',
    'up_one'        => 'tillbaka&nbsp;till&nbsp;f&ouml;reg&aring;ende'
                     . '&nbsp;sida',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Danish - courtesy of Mikkel Mondrup Kristensen <hh00d-mmk@uv.horshs.dk>
$mig_messages['da'] = array (
    'backhome'      => 'tilbage&nbsp;til',
    'bytes'         => '&nbsp;bytes',
    'flash_used'    => 'flash used',
    'main'          => 'Main',
    'must_auth'     => 'Du skal skrive et gyldigt brugernavn og password'
                     . ' for at komme ind',
    'nextimage'     => 'n&#230;ste&nbsp;billed',
    'no_contents'   => 'intet&nbsp;inhold.',
    'previmage'     => 'forrige&nbsp;billed',
    'thumbview'     => 'tilbage&nbsp;til&nbsp;oversigten',
    'up_one'        => 'tilbage',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Italian - courtesy of Marco Ermini <markoer@firenze.linux.it>
$mig_messages['it'] = array (
    'backhome'     => 'torna&nbsp;a',
    'bytes'        => '&nbsp;bytes',
    'flash_used'   => 'usato flash',
    'main'         => 'Principale',
    'must_auth'    => 'Devi inserire un nome utente ed una password validi'
                    . ' per accedere',
    'nextimage'    => 'prossima&nbsp;immagine',
    'no_contents'  => 'Vuoto.',
    'previmage'    => 'immagine&nbsp;precedente',
    'thumbview'    => 'torna&nbsp;alla&nbsp;vista&nbsp;per&nbsp;icone',
    'up_one'       => 'torna&nbsp;al&nbsp;livello&nbsp;superiore',
    'month'        => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                              '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                              '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                              '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Spanish - courtesy of Alex Dantart <alex@pixar.es>
// with some adjustments from JMN <umjumasa@terra.es>
$mig_messages['es'] = array (
    'backhome'     => 'regresar&nbsp;inicio',
    'bytes'        => '&nbsp;bytes',
    'flash_used'   => 'flash usado',
    'main'         => 'Principal',
    'must_auth'    => 'Debes introducir un usuario y clave v&#225;lidas'
                    . ' para acceder',
    'nextimage'    => 'siguiente&nbsp;im&#225;gen',
    'no_contents'  => 'Sin&nbsp;contenidos.',
    'previmage'    => 'anterior&nbsp;im&#225;gen',
    'thumbview'    => 'volver&nbsp;a&nbsp;vista&nbsp;por&nbsp;iconos',
    'up_one'       => 'subir&nbsp;un&nbsp;nivel',
    'month'        => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                              '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                              '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                              '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Slovak - courtesy of Juro Polak <polak@axon.sk>
$mig_messages['sk'] = array (
    'backhome'      => 'sp&#228;?&nbsp;na',
    'bytes'         => '&nbsp;bytov',
    'flash_used'    => 'flash used',
    'main'          => 'Hlavn&#225;&nbsp;str&#225;nka',
    'must_auth'     => 'Mus&#237;te uvies? u?&#237;vate&#190;sk&#233; meno'
                     . ' a heslo na vstup',
    'nextimage'     => '&#239;al?&#237;&nbsp;obr&#225;zok',
    'no_contents'   => 'Pr&#225;zdny&nbsp;adres&#225;r.',
    'previmage'     => 'predch&#225;dzaj&#250;ci&nbsp;obr&#225;zok',
    'thumbview'     => 'sp&#228;?&nbsp;na&nbsp;zmen?eniny&nbsp;obr&#225;zkov'
                     . '(thumbnail)',
    'up_one'        => 'o&nbsp;&#250;rove&#242;&nbsp;vy??ie',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Dutch - courtesy of Erik@Braindisorder.org
$mig_messages['nl'] = array (
    'backhome'      => 'Terug&nbsp;naar',
    'bytes'         => '&nbsp;bytes',
    'flash_used'    => 'flash used',
    'main'          => 'Hoofdmenu',
    'must_auth'     => 'Je moet een geldige naam en wachtwoord invoeren'
                     . 'om hier naar binnen te gaan',
    'nextimage'     => 'volgende&nbsp;foto',
    'no_contents'   => 'Geen&nbsp;commentaar.',
    'previmage'     => 'vorige&nbsp;foto',
    'thumbview'     => 'terug&nbsp;naar&nbsp;overzicht',
    'up_one'        => 'een&nbsp;niveau&nbsp;omhoog',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);

// Polish - courtesy of martvin@box43.pl
$mig_messages['pl'] = array (
    'backhome'      => 'powr&#243;t&nbsp;do',
    'bytes'         => '&nbsp;bajt&#243;w',
    'flash_used'    => 'u&#191;yto&nbsp;Flash',
    'main'          => 'G&#179;&#243;wna',
    'must_auth'     => 'Musisz wpisa&#230; prawid&#179;ow&#185; nazw&#234;'
                     . 'u&#191;ytkownika i has&#179;o by wejœ&#230;',
    'nextimage'     => 'nast&#234;pny&nbsp;obrazek',
    'no_contents'   => 'nie&nbsp;ma zawartoœci.',
    'previmage'     => 'poprzedni&nbsp;obrazek',
    'thumbview'     => 'powr&#243;t&nbsp;do&nbsp;widoku&nbsp;miniatur',
    'up_one'        => 'powr&#243;t&nbsp;&nbsp;',
    'month'         => array ( '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                               '04' => 'Apr', '05' => 'May', '06' => 'Jun',
                               '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
                               '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
);


// ======================================================================
// FUNCTION DEFINITIONS
//


// parseMigCf - Parse a mig.cf file for sort and hidden blocks

function parseMigCf ( $directory, $useThumbSubdir, $thumbSubdir )
{

    // What file to parse
    $cfgfile = 'mig.cf';

    // Prototypes
    $hidden         = array ();
    $presort_dir    = array ();
    $presort_img    = array ();
    $desc           = array ();
    $ficons         = array ();

    // Hide thumbnail subdirectory if one is in use.
    if ($useThumbSubdir) {
        $hidden[$thumbSubdir] = TRUE;
    }

    if (file_exists("$directory/$cfgfile")) {
        $file = fopen("$directory/$cfgfile", 'r');
        $line = fgets($file, 4096);     // get first line

        while (! feof($file)) {

            // Parse <hidden> blocks
            if (eregi('^<hidden>', $line)) {
                $line = fgets($file, 4096);
                while (! eregi('^</hidden>', $line)) {
                    $line = trim($line);
                    $hidden[$line] = TRUE;
                    $line = fgets($file, 4096);
                }
            }

            // Parse <sort> structure
            if (eregi('^<sort>', $line)) {
                $line = fgets($file, 4096);
                while (! eregi('^</sort>', $line)) {
                    $line = trim($line);
                    if (is_file("$directory/$line")) {
                        $presort_img[$line] = TRUE;
                    } elseif (is_dir("$directory/$line")) {
                        $presort_dir[$line] = TRUE;
                    }
                    $line = fgets($file, 4096);
                }
            }

            // Parse <bulletin> structure
            if (eregi('^<bulletin>', $line)) {
                $line = fgets($file, 4096);
                while (! eregi('^</bulletin>', $line)) {
                    $bulletin .= $line;
                    $line = fgets($file, 4096);
                }
            }

            // Parse <comment> structure
            if (eregi('^<comment', $line)) {
                $commfilename = trim($line);
                $commfilename = str_replace('">', '', $commfilename);
                $commfilename = eregi_replace('^<comment "','',$commfilename);
                $line = fgets($file, 4096);
                while (! eregi('^</comment', $line)) {
                    $line = trim($line);
                    $mycomment .= "$line ";
                    $line = fgets($file, 4096);
                }
                $desc[$commfilename] = $mycomment;
                $commfilename = '';
                $mycomment = '';
            }

            // Parse FolderIcon lines
            if (eregi('^foldericon ', $line)) {
                $x = trim($line);
                list($y, $folder, $icon) = explode(' ', $x);
                $ficons[$folder] = $icon;
            }

            // Parse FolderTemplate lines
            if (eregi('^foldertemplate ', $line)) {
                $x = trim($line);
                list($y, $template) = explode(' ', $x);
            }

            // Parse PageTitle lines
            if (eregi('^pagetitle ', $line)) {
                $x = trim($line);
                $pagetitle = eregi_replace('^pagetitle ', '', $x);
            }

            // Parse MaintAddr lines
            if (eregi('^maintaddr ', $line)) {
                $x = trim($line);
                $maintaddr = eregi_replace('^maintaddr ', '', $x);
            }

            // Parse MaxFolderColumns lines
            if (eregi('^maxfoldercolumns ', $line)) {
                $x = trim($line);
                list($y, $fcols) = explode(' ', $x);
            }

            // Parse MaxThumbColumns lines
            if (eregi('^maxthumbcolumns ', $line)) {
                $x = trim($line);
                list($y, $tcols) = explode(' ', $x);
            }

            // Get next line
            $line = fgets($file, 4096);

        } // end of main while() loop

        fclose($file);
    }

    $retval = array ($hidden, $presort_dir, $presort_img, $desc,
                     $bulletin, $ficons, $template, $pagetitle,
                     $fcols, $tcols, $maintaddr);
    return $retval;

}   //  -- End of parseMigCf()



// printTemplate() - prints HTML page from a template file

function printTemplate ( $baseURL, $templateDir, $templateFile, $version,
                         $maintAddr, $folderList, $imageList, $backLink,
                         $albumURLroot, $image, $currDir, $newCurrDir,
                         $pageTitle, $prevLink, $nextLink, $currPos,
                         $description, $youAreHere, $distURL, $albumDir,
                         $server, $useVirtual )
{

    if (! ereg('^/', $templateFile)) {
        $templateFile = $albumDir . '/' . $newCurrDir . '/' . $templateFile;
    }

    // Panic if the template file doesn't exist.
    if (! file_exists($templateFile)) {
        print "ERROR: $templateFile does not exist!";
        exit;
    }

    $file = fopen($templateFile,'r');    // Open template file
    $line = fgets($file, 4096);                         // Get first line

    while (! feof($file)) {             // Loop until EOF

        // Look for include directives and process them
        if (ereg('^#include', $line)) {
            $orig_line = $line;
            $line = trim($line);
            $line = str_replace('#include "', '', $line);
            $line = str_replace('";', '', $line);
            if (strstr($line, '/')) {
                $line = '<!-- ERROR: #include directive failed.'
                      . ' Path included a "/" character, indicating'
                      . ' an absolute or relative path.  All included'
                      . ' files must be located in the templates/'
                      . ' subdirectory. Directive was:'
                      . "\n     $orig_line\n-->\n";
                print $line;
            } else {
                $incl_file = $line;
                if (file_exists("$templateDir/$incl_file")) {

                    // Is this a PHP file?
//                    if (eregi('.php3?',  $incl_file)) {
//                        // include as php
//                        include("$templateDir/$incl_file");
//
//                    } else {        // Not PHP, either CGI or just text
//
//                        // virtual() only works for Apache
//                        if (ereg('^Apache', $server) and $useVirtual) { 
//                            // virtual() doesn't like absolute paths,
//                            // apparently, so just pass it a relative one.
//                            $tmplDir = ereg_replace("^.*/", "", $templateDir);
//                            virtual("$tmplDir/$incl_file");
//                        } else {
//                            // readfile() just spits a file to stdout
//                            readfile("$templateDir/$incl_file");
//                        }
//                    }

                    if (function_exists('virtual')) {
                        // virtual() doesn't like absolute paths,
                        // apparently, so just pass it a relative one.
                        $tmplDir = ereg_replace("^.*/", "", $templateDir);
                        virtual("$tmplDir/$incl_file");
                    } else {
                        include("$templateDir/$incl_file");
                    }

                } else {
                    // If the file doesn't exist, complain.
                    $line = '<!-- ERROR: #include directive failed.'
                          . ' Named file ' . $incl_file
                          . ' does not exist.  Directive was:'
                          . "\n    $orig_line\n-->\n";
                    print $line;
                }
            }

        } else {

            // Make sure this is URL encoded
            $encodedImageURL = migURLencode($image);

            if ($image) {
                // Get image pixel size for <IMG> element
                $imageProps = GetImageSize("$albumDir/$currDir/$image");
                $imageSize = $imageProps[3];
            }

            // List of valid tags
            $replacement_list = array (
                'baseURL', 'maintAddr', 'version', 'folderList',
                'imageList', 'backLink', 'currDir', 'newCurrDir',
                'image', 'albumURLroot', 'pageTitle', 'nextLink',
                'prevLink', 'currPos', 'description', 'youAreHere',
                'distURL', 'encodedImageURL', 'imageSize'
            );

            // Do substitution for various variables
            while (list($key,$val) = each($replacement_list)) {
                $line = str_replace("%%$val%%", $$val, $line);
            }

            print $line;                // Print resulting line
        }
        $line = fgets($file, 4096);     // Grab another line
    }

    fclose($file);
    return TRUE;

}    // -- End of printTemplate()



// buildDirList() - creates list of directories available

function buildDirList ( $baseURL, $albumDir, $currDir, $imageDir,
                        $useThumbSubdir, $thumbSubdir, $maxColumns,
                        $hidden, $presorted, $viewFolderCount,
                        $markerType, $markerLabel, $ficons )
{

    $oldCurrDir = $currDir;         // Stash this to build full path with

    // Create a URL-encoded version of $currDir
    $enc_currdir = $currDir;
    $currDir = rawurldecode($enc_currdir);

    $dir = opendir("$albumDir/$currDir");       // Open directory handle

    $directories = array ();                    // prototypes
    $counts = array ();

    if ($viewFolderCount) {
        while(list($file,$x) = each($presorted)) {
            $folder = "$albumDir/$currDir/$file";
            $counts[$file] = getNumberOfImages($folder,
                                $useThumbSubdir, $markerType,
                                $markerLabel);
        }
        reset($presorted);
    }

    while ($file = readdir($dir)) {

        // Ignore . and .. and make sure it's a directory
        if ($file != '.' and $file != '..'
            and is_dir("$albumDir/$currDir/$file")) {

            // Ignore anything that's hidden or was already sorted.
            if (!$hidden[$file] and !$presorted[$file]) {

                // Stash file in an array
                $directories[$file] = TRUE;

                // Get a count of the images it contains, if
                // desired.
                if ($viewFolderCount) {
                    $folder = "$albumDir/$currDir/$file";
                    $counts[$file] = getNumberOfImages($folder,
                                        $useThumbSubdir, $markerType,
                                        $markerLabel);
                }
            }
        }
    }

    ksort($directories);    // sort so we can yank them in sorted order
    reset($directories);    // reset array pointer to beginning

    // snatch each element from $directories and shove it on the end of
    // $presorted
    while (list($file,$junk) = each($directories)) {
        $presorted[$file] = TRUE;
    }

    reset($presorted);          // reset array pointer

    // Track columns
    $row = 0;
    $col = 0;
    $maxColumns--;  // Tricks $maxColumns into working since it
                    // really starts at 0, not 1

    while (list($file,$junk) = each($presorted)) {

        // Start a new row if appropriate
        if ($col == 0) {
            $directoryList .= '<tr>';
        }

        // Surmise the full path to work with
        $newCurrDir = $oldCurrDir . '/' . $file;

        // URL-encode the directory name in case it contains spaces
        // or other weirdness.
        $enc_file = migURLencode($newCurrDir);

        // Build the link itself for re-use below
        $linkURL = '<a href="' . $baseURL
                 . '?pageType=folder&currDir=' . $enc_file . '">';

        // Reword $file so it doesn't allow wrapping of the label
        // (fixes odd formatting bug in MSIE).
        // Also, render _ as a space.
        $nbspfile = $file;
        $nbspfile = str_replace(' ', '&nbsp;', $nbspfile);
        $nbspfile = str_replace('_', '&nbsp;', $nbspfile);

        // Build the full link (icon plus folder name) and tack it on
        // the end of the list.
        $directoryList .= '<td class="folder">' . $linkURL . '<img src="'
                       . $imageDir . '/';
        if ($ficons[$file]) {
            $directoryList .= $ficons[$file];
        } else {
            $directoryList .= 'folder.gif';
        }
        $directoryList .= '" border="0"></a>&nbsp;'
                       . $linkURL . '<font size="-1">' . $nbspfile
                       . '</font></a>';
        if ($viewFolderCount and $counts[$file] > 0) {
            $directoryList .= ' (' . $counts[$file] . ')';
        }
        $directoryList .= '</td>';

        // Keep track of what row/column we're on
        if ($col == $maxColumns) {
            $directoryList .= '</tr>';
            $row++;
            $col = 0;
        } else {
            $col++;
        }
    }

    closedir($dir); 

    // If there aren't any subfolders to look at, then just say so.
    if ($directoryList == '') {
        return 'NULL';

    } elseif (!eregi('</tr>$', $directoryList)) {

        // Stick a </tr> on the end if it isn't there already
        $directoryList .= '</tr>';
    }

    return $directoryList;

} // -- End of buildDirList()



// buildImageList() - creates a list of images available

function buildImageList( $baseURL, $baseDir, $albumDir, $currDir,
                         $albumURLroot, $maxColumns, $directoryList,
                         $markerType, $markerLabel, $suppressImageInfo,
                         $useThumbSubdir, $thumbSubdir, $noThumbs,
                         $thumbExt, $suppressAltTags, $sortType, $hidden,
                         $presorted, $description, $imagePopup,
                         $imagePopType, $commentFilePerImage )
{
    global $mig_language;
    global $mig_messages;

    $dir = opendir("$albumDir/$currDir");       // Open directory handle

    $row = 0;               // Counters for the table formatting
    $col = 0;

    $maxColumns--;          // Tricks maxColumns into working since it
                            // really starts at 0, not 1.

    // prototype the arrays
    $imagefiles     = array ();
    $filedates      = array ();

    while ($file = readdir($dir)) {
        // Skip over thumbnails
        if (!$useThumbSubdir) {  // unless $useThumbSubdir is set,
                                 // then don't waste time on this check

            if ($markerType == 'suffix'
                and eregi("_$markerLabel\.(gif|jpg|png|jpeg|jpe)$", $file)) {
                    continue;
            }

            if ($markerType == 'prefix' and ereg("^$markerLabel\_", $file)) {
                continue;
            }

        }

        // We'll look at this one only if it's a file, it's not hidden,
        // and it matches our list of approved extensions
        $ext = getFileExtension($file);
        if (is_file("$albumDir/$currDir/$file") and !$hidden[$file]
                        and !$presorted[$file]
                        and eregi('^(jpg|gif|png|jpeg|jpe)$', $ext))
        {
            // Stash file in an array
            $imagefiles[$file] = TRUE;
            // and stash a timestamp as well if needed
            if (ereg("bydate.*", $sortType)) {
                $timestamp = filemtime("$albumDir/$currDir/$file");
                $filedates["$timestamp-$file"] = $file;
            }
        }
    }

    ksort($imagefiles); // sort, so we get a sorted list to stuff onto the
                        // end of $presorted

    reset($imagefiles); // reset array pointer

    if ($sortType == "bydate-ascend") {
        ksort($filedates);
        reset($filedates);

    } elseif ($sortType == "bydate-descend") {
        krsort($filedates);
        reset($filedates);
    }

    // Join the two sorted lists together into a single list
    if (ereg("bydate.*", $sortType)) {
        while(list($junk,$file) = each($filedates)) {
            $presorted[$file] = TRUE;
        }

    } else {
        while (list($file,$junk) = each($imagefiles)) {
            $presorted[$file] = TRUE;
        }
    }

    reset($presorted);          // reset array pointer

    while (list($file,$junk) = each($presorted)) {

        // Only look at valid image types
        $ext = getFileExtension($file);
        if (eregi('^(jpg|gif|png|jpeg|jpe)$', $ext)) {

            // If this is a new row, start a new <TR>
            if ($col == 0) {
                $imageList .= '<tr>';
            }

            $fname = getFileName($file);
            $img = buildImageURL($baseURL, $baseDir, $albumDir, $currDir,
                                 $albumURLroot, $fname, $ext, $markerType,
                                 $markerLabel, $suppressImageInfo,
                                 $useThumbSubdir, $thumbSubdir, $noThumbs,
                                 $thumbExt, $suppressAltTags, $description,
                                 $imagePopup, $imagePopType,
                                 $commentFilePerImage);
            $imageList .= $img;

            // Keep track of what row and column we are on
            if ($col == $maxColumns) {
                $imageList .= '</tr>';
                $row++;
                $col = 0;
            } else {
                $col++;
            }
        }
    }

    closedir($dir);

    // If there aren't any images to work with, just say so.
    if ($imageList == '') {
        $imageList = 'NULL';
    } elseif (!eregi('</tr>$', $imageList)) {
        // Stick a </tr> on the end if it isn't there already.
        $imageList .= '</tr>';
    }

    return $imageList;

}   // -- End of buildImageList()



// buildBackLink() - spits out a "back one section" link

function buildBackLink( $baseURL, $currDir, $type, $homeLink, $homeLabel,
                        $noThumbs)
{
    global $mig_language;
    global $mig_messages;

    // $type notes whether we want a "back" link or "up one level" link.
    if ($type == 'back' or $noThumbs) {
        //$label = 'up&nbsp;one&nbsp;level';
        $label = $mig_messages[$mig_language]['up_one'];
    } elseif ($type == 'up') {
        //$label = 'back&nbsp;to&nbsp;thumbnail&nbsp;view';
        $label = $mig_messages[$mig_language]['thumbview'];
    }

    // don't send a link back if we're a the root of the tree
    if ($currDir == '.') {
        if ($homeLink != '') {

            if ($homeLabel == '') {
                $homeLabel = $homeLink;
            } else {
                // Get rid of spaces due to silly formatting in MSIE
                $homeLabel = str_replace(' ', '&nbsp;', $homeLabel);
            }

            // Build a link to the "home" page
            $retval  = '<font size="-1">[&nbsp;<a href="'
                     . $homeLink
                     . '">'
                     . $mig_messages[$mig_language]['backhome']
                     . '&nbsp;'
                     . $homeLabel
                     . '</a>&nbsp;]</font><br><br>';
        } else {
            $retval = '<br>';
        }
        return $retval;
    }

    // Trim off the last directory, so we go "back" one.
    $junk = ereg_replace('/[^/]+$', '', $currDir);
    $newCurrDir = migURLencode($junk);

    $retval = '<font size="-1">[&nbsp;<a href="'
            . $baseURL . '?currDir=' . $newCurrDir . '">' . $label
            . '</a>&nbsp;]</font><br><br>';

    return $retval;

}   // -- End of buildBackLink()



// buildImageURL() -- spit out HTML for a particular image

function buildImageURL( $baseURL, $baseDir, $albumDir, $currDir,
                        $albumURLroot, $fname, $ext, $markerType,
                        $markerLabel, $suppressImageInfo, $useThumbSubdir,
                        $thumbSubdir, $noThumbs, $thumbExt, $suppressAltTags,
                        $description, $imagePopup, $imagePopType,
                        $commentFilePerImage )
{
    global $mig_language;
    global $mig_messages;

    // newCurrDir is currDir without leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // URL-encode currDir, keeping an old copy too
    $oldCurrDir = $currDir;
    $currDir = migURLencode($currDir);

    // URL-encoded the filename
    $newFname = rawurlencode($fname);

    // Only show a thumbnail if one exists.  Otherwise use a default
    // "generic" thumbnail image.

    if ($useThumbSubdir) {

        if ($thumbExt) {
            $thumbFile = "$albumDir/$oldCurrDir/$thumbSubdir/$fname.$thumbExt";
        } else {
            $thumbFile = "$albumDir/$oldCurrDir/$thumbSubdir/$fname.$ext";
        }

    } else {

        if ($markerType == 'prefix') {
            $thumbFile  = "$albumDir/$oldCurrDir/$markerLabel";

            if ($thumbExt) {
                $thumbFile .= "_$fname.$thumbExt";
            } else {
                $thumbFile .= "_$fname.$ext";
            }
        }

        if ($markerType == 'suffix') {
            $thumbFile  = "$albumDir/$oldCurrDir/$fname";
            if ($thumbExt) {
                $thumbFile .= "_$markerLabel.$thumbExt";
            } else {
                $thumbFile .= "_$markerLabel.$ext";
            }
        }
    }

    if (file_exists($thumbFile)) {
        if ($useThumbSubdir) {
            $thumbImage  = "$albumURLroot/$currDir/$thumbSubdir";
            if ($thumbExt) {
                $thumbImage .= "/$fname.$thumbExt";
            } else {
                $thumbImage .= "/$fname.$ext";
            }

        } else {

            if ($markerType == 'prefix') {
                $thumbImage  = "$albumURLroot/$currDir/$markerLabel";
                if ($thumbExt) {
                    $thumbImage .= "_$fname.$thumbExt";
                } else {
                    $thumbImage .= "_$fname.$ext";
                }
            }

            if ($markerType == 'suffix') {
                $thumbImage  = "$albumURLroot/$currDir/$fname";
                if ($thumbExt) {
                    $thumbImage .= "_$markerLabel.$thumbExt";
                } else {
                    $thumbImage .= "_$markerLabel.$ext";
                }
            }
        }
        $thumbImage = migURLencode($thumbImage);
    } else {
        $newRoot = ereg_replace('/[^/]+$', '', $baseURL);
        $thumbImage = $newRoot . '/images/no_thumb.gif';
    }

    // Get description, if any
    if ($commentFilePerImage) {
        $alt_desc = getImageDescFromFile("$fname.$ext", $albumDir, $currDir);
    } else {
        $alt_desc = getImageDescription("$fname.$ext", $description);
    }
    $alt_desc = strip_tags($alt_desc);

    // if both are present, separate with "--"
    //if ($alt_desc and $alt_exif) {
    //    $alt_desc .= " -- $alt_exif";
    //}

    // Figure out the image's size (in bytes and pixels) for display
    $imageFile = "$albumDir/$oldCurrDir/$fname.$ext";

    // Figure out the pixels
    $imageProps = GetImageSize($imageFile);
    $imageWidth = $imageProps[0];
    $imageHeight = $imageProps[1];

    // Figure out the bytes
    $imageSize = filesize($imageFile);
    if ($imageSize > 1048576) {
        $imageSize = sprintf('%01.1f', $imageSize / 1024 / 1024) . 'MB';
    } elseif ($imageSize > 1024) {
        $imageSize = sprintf('%01.1f', $imageSize / 1024) . 'KB';
    } else {
        $imageSize = $imageSize . $mig_messages[$mig_language]['bytes'];
    }

    // Figure out thumbnail geometry
    $thumbHTML = '';
    if (file_exists($thumbFile)) {
        $thumbProps = GetImageSize($thumbFile);
        $thumbHTML = $thumbProps[3];
    }

    // beginning of the table cell
    $url = '<td class="image"><a';
    if (!$suppressAltTags) {
        $url .= ' title="' . $alt_desc . '"';
    }
    $url .= ' href="';

    // set up the image pop-up if appropriate to do so
    if ($imagePopup) {
        $popup_width = $imageWidth + 30;
        $popup_height = $imageHeight + 150;
        $url .= '#" onClick="window.open(\'';
    }

    $url .= $baseURL . '?currDir='
         . $currDir . '&pageType=image&image=' . $newFname
         . '.' . $ext;

    if ($imagePopup) {
        $url .= "','";
        if ($imagePopType == 'reuse') {
            $url .= 'mig_window_11190874';
        } else {
            $url .= 'mig_window_' . time() . '_' . $newFname;
        }
        $url .= "','width=$popup_width,height=$popup_height,"
              . "resizable=yes,scrollbars=1');";
    }

    $url .= '">';

    // If $noThumbs is true, just print the image filename rather
    // than the <IMG> tag pointing to a thumbnail.
    if ($noThumbs) {
        $url .= "$newFname.$ext";
    } else {
        $url .= '<img src="' . $thumbImage . '"';
            // Only print the ALT tag if it's wanted.
            if (! $suppressAltTags) {
                $url .= ' alt="' . $alt_desc . '"';
            }
        $url .= ' border="0" ' . $thumbHTML . '>';
    }

    $url .= '</a>';     // End the <A> element

    // If $suppressImageInfo is FALSE, show the image info
    if (!$suppressImageInfo) {
        $url .= '<br><font size="-1">';
        if (!$noThumbs) {
            $url .= $fname . '.' . $ext . '<br>';
        }
        $url .= '(' . $imageWidth . 'x' . $imageHeight . ', '
             . $imageSize . ')</font>';
    }

    $url .= '</td>';        // Close table cell
    return $url;

}   // -- End of buildImageURL()



// buildNextPrevLinks() -- Build a link to the "next" and "previous"
// images.

function buildNextPrevLinks( $baseURL, $albumDir, $currDir, $image,
                             $markerType, $markerLabel, $hidden,
                             $presorted, $sortType )
{
    global $mig_language;
    global $mig_messages;

    // newCurrDir is currDir without the leading './'
    $newCurrDir = getNewCurrDir($currDir);

    $dir = opendir("$albumDir/$currDir");// Open directory handle

    // Gather all files into an array
    $fileList = array ();
    while ($file = readdir($dir)) {

        // Ignore thumbnails
        if ($markerType == 'prefix' and ereg("^$markerLabel\_", $file)) {
            continue;
        }
        if ($markerType == 'suffix'
            and eregi("_$markerLabel\.(gif|jpg|png|jpeg|jpe)$", $file)) {
                continue;
        }

        // Only look at valid image formats
        if (!eregi('\.(gif|jpg|png|jpeg|jpe)$', $file)) {
            continue; 
        }
        // Ignore the hidden images
        if ($hidden[$file]) {
            continue;
        }
        // Make sure this is a file, not a directory.
        // and make sure it isn't presorted
        if (is_file("$albumDir/$currDir/$file") and ! $presorted[$file]) {
            $fileList[$file] = TRUE;
            // Store a date, too, if needed
            if (ereg("bydate.*", $sortType)) {
                $timestamp = filemtime("$albumDir/$currDir/$file");
                $filedates["$timestamp-$file"] = $file;
            }
        }
    }

    closedir($dir); 

    ksort($fileList);       // sort, so we see sorted results
    reset($fileList);       // reset array pointer

    if ($sortType == "bydate-ascend") {
        ksort($filedates);
        reset($filedates);

    } elseif ($sortType == "bydate-descend") {
        krsort($filedates);
        reset($filedates);
    }

    // Generated final sorted list
    if (ereg("bydate.*", $sortType)) {
        // since $filedates is sorted by date, and date is
        // the key, the key is pointless to put in the list now.
        // so we store the value, not the key, in $presorted
        while (list($junk,$file) = each($filedates)) {
            $presorted[$file] = TRUE;
        }

    } else {
        // however, here we have real data in the key, so we push
        // the key, not the value, into $presorted.
        while (list($file,$junk) = each($fileList)) {
            $presorted[$file] = TRUE;
        }
    }

    reset($presorted);      // reset array pointer

    // Gather all files into an array

    $i = 1;                 // iteration counter, etc

    // Yes, position 0 is garbage.  Makes the math easier later.
    $fList = array ( 'blah' ); 

    while (list($file, $junk) = each($presorted)) {
    
        // If "this" is the one we're looking for, mark it as such.
        if ($file == $image) {
            $ThisImagePos = $i;
        }
        $fList[$i] = $file;     // Stash filename in the array
        $i++;                   // increment the counter, of course.
    } 
    reset($fList);

    $i--;                       // Get rid of the last increment...

    // Next is one more than $ThisImagePos.  Test if that has a value
    // and if it does, consider it "next".
    if ($fList[$ThisImagePos+1]) {
        $next = migURLencode($fList[$ThisImagePos+1]);
    } else {
        $next = 'NA';
    }

    // Previous must always be one less than the current index.  If
    // that has a value, that is.  Unless the current index is "1" in
    // which case we know there is no previous.
    
    if ($ThisImagePos == 1) {
        $prev = 'NA';
    } elseif ($fList[$ThisImagePos-1]) {
        $prev = migURLencode($fList[$ThisImagePos-1]); 
    }

    // URL-encode currDir
    $currDir = migURLencode($currDir);

    // newCurrDir is currDir without the leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // If there is no previous image, show a greyed-out link
    if ($prev == 'NA') {
        $pLink = '<font size="-1">[&nbsp;<font color="#999999">'
               . $mig_messages[$mig_language]['previmage']
               . '</font>&nbsp;]</font>';

    // else show a real link
    } else {
        $pLink = '<font size="-1">[&nbsp;<a href="' . $baseURL
               . '?pageType=image&currDir=' . $currDir . '&image='
               . $prev . '">' . $mig_messages[$mig_language]['previmage']
               . '</a>&nbsp;]</font>';
    }

    // If there is no next image, show a greyed-out link
    if ($next == 'NA') {
        $nLink = '<font size="-1">[&nbsp;<font color="#999999">'
               . $mig_messages[$mig_language]['nextimage']
               . '</font>&nbsp;]</font>';
    // else show a real link
    } else {
        $nLink = '<font size="-1">[&nbsp;<a href="' . $baseURL
               . '?pageType=image&currDir=' . $currDir . '&image='
               . $next . '">' . $mig_messages[$mig_language]['nextimage']
               . '</a>&nbsp;]</font>';
    }

    // Current position in the list
    $currPos = '#' . $ThisImagePos . '&nbsp;of&nbsp;' . $i;

    $retval = array( $nLink, $pLink, $currPos );
    return $retval;

}   // -- End of buildNextPrevLinks()



// buildYouAreHere() - build the "You are here" line for the top
// of each page

function buildYouAreHere( $baseURL, $currDir, $image )
{
    global $mig_language;
    global $mig_messages;

    // Use $workingCopy so we don't trash value of $currDir
    $workingCopy = $currDir;

    // Loop until we get down to just the '.'
    while ($workingCopy != '.') {

        // $label is the "last" thing in the path. Strip up to that
        $label = ereg_replace('^.*/', '', $workingCopy);
        // Render underscores as spaces and turn spaces into &nbsp;
        $label = str_replace('_', '&nbsp;', $label);
        $label = str_replace(' ', '&nbsp;', $label);

        // Get a URL-encoded copy of $workingCopy
        $encodedCopy = migURLencode($workingCopy);

        if ($image == '' and $workingCopy == $currDir) {
            $url = '&nbsp;:&nbsp;<b>' . $label . '</b>';
        } else {
            $url = '&nbsp;:&nbsp;<a href="' . $baseURL . '?currDir='
                 . $encodedCopy . '">' . $label . '</a>';
        }

        // Strip the last piece off of $workingCopy to go to next loop
        $workingCopy = ereg_replace('/[^/]+$', '', $workingCopy);

        // Build up the final path over each loop iteration
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If we're down to '.' as our currDir then this is 'Main'
    if ($currDir == '.') {
        $url = '<b>' . $mig_messages[$mig_language]['main'] . '</b>';
        $x = $hereString;
        $hereString = $url . $x;

    // Or if we're not, then Main should be a link instead of just text
    } else {
        $url = '<a href="' . $baseURL . '?currDir=' . $workingCopy
             . '">' . $mig_messages[$mig_language]['main'] . '</a>';
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If there's an image, tack it onto the end of the hereString
    if ($image != '') {
        $hereString .= '&nbsp;:&nbsp;<b>' . $image . '</b>';
    }

    $x = $hereString;
    $hereString = '<font size="-1">' . $x . '</font>';
    return $hereString;

}   // -- End of buildYouAreHere()



// getFileExtension() - figure out a file's extension and return it.

function getFileExtension( $file )
{
    // Strip off the extension part of the filename
    $ext = ereg_replace('^.*\.', '', $file);

    return $ext;

}   // -- End of getFileExtension()



// getFileName() - figure out a file's name sans extension.

function getFileName( $file )
{
    // Strip off the non-extension part of the filename
    $fname = ereg_replace('\.[^\.]+$', '', $file);

    return $fname;

}   // -- End of getFileName()



// getImageDescription() - Fetches an image description from the
// comments file (mig.cf)

function getImageDescription( $image, $description )
{
    $imageDesc = '';
    if ($description[$image]) {
        $imageDesc = $description[$image];
    }
    return $imageDesc;

}   // -- End of getImageDescription()



// getImageDescFromFile() - Fetches an image description from a
// per-image comment file (used if $commentFilePerImage is TRUE)

function getImageDescFromFile( $image, $albumDir, $currDir )
{
    $imageDesc = '';
    $fname = getFileName($image);

    if (file_exists("$albumDir/$currDir/$fname.txt")) {

        $file = fopen("$albumDir/$currDir/$fname.txt", 'r');
        $line = fgets($file, 4096);     // get first line

        while (!feof($file)) {
            $line = trim($line);
            $imageDesc .= "$line ";
            $line = fgets($file, 4096); // get next line
        }

        fclose($file);
    }

    return $imageDesc;

}   // -- End of getImageDescFromFile();



// getExifDescription() - Fetches a comment if available from the
// Exif comments file (exif.inf) as well as fetching EXIF data

function getExifDescription( $albumDir, $currDir, $image, $viewCamInfo,
                             $viewDateInfo)
{
    // Use global language settings
    global $mig_messages;
    global $mig_language;

    $desc = array ();
    $model = array ();
    $shutter = array ();
    $aperture = array ();
    $foclen = array ();
    $flash = array ();
    $iso = array ();
    $timestamp = array ();

    if (file_exists("$albumDir/$currDir/exif.inf")) {

        $file = fopen("$albumDir/$currDir/exif.inf", 'r');
        $line = fgets($file, 4096);     // get first line
        while (!feof($file)) {

            if (ereg('^File name    : ', $line)) {
                $fname = ereg_replace('^File name    : ', '', $line);
                $fname = chop($fname);

            } elseif (ereg('^Comment      : ', $line)) {
                $comment = ereg_replace('^Comment      : ', '', $line);
                $comment = chop($comment);
                $desc[$fname] = $comment;

            }

            if ($viewCamInfo) {
            
                if (ereg('^Camera model : ', $line)) {
                    $x = ereg_replace('^Camera model : ', '', $line);
                    $x = chop($x);
                    $model[$fname] = $x;

                // This one apparently sometimes has a space after
                // the colon, sometimes not.  Try to work either way.
                } elseif (ereg('^Exposure time: ?', $line)) {
                    $x = ereg_replace('^Exposure time: ?', '', $line);
                    if (ereg('\(', $x)) {
                        $x = ereg_replace('^.*\(', '', $x);
                        $x = ereg_replace('\).*$', '', $x);
                    }
                    $x = chop($x);
                    $shutter[$fname] = $x;

                } elseif (ereg('^Aperture     : ', $line)) {
                    $x = ereg_replace('^Aperture     : ', '', $line);
                    // make it fN.N instead of f/N.N
                    $x = ereg_replace('/', '', $x);
                    $x = chop($x);
                    $aperture[$fname] = $x;

                } elseif (ereg('^Focal length : ', $line)) {
                    $x = ereg_replace('^Focal length : ', '', $line);
                    if (ereg('35mm equiv', $x)) {
                        $x = ereg_replace('^.*alent: ', '', $x);
                        $x = chop($x);
                        $x = ereg_replace('\)$', '', $x);
                    }
                    $foclen[$fname] = $x;

                } elseif (ereg('^ISO equiv.   : ', $line)) {
                    $x = ereg_replace('ISO equiv.   : ', '', $line);
                    $x = chop($x);
                    $iso[$fname] = $x;

                } elseif (ereg('^Flash used   : Yes', $line)) {
                    $flash[$fname] = TRUE;

                } elseif (ereg('^Date/Time    : ', $line)) {
                    $x = ereg_replace('Date/Time    : ', '', $line);
                    $x = chop($x);

                    // Turn into human readable format and record
                    $timestamp[$fname] = parseExifDate($x);
                }
            }

            $line = fgets($file, 4096);
        }

        fclose($file);

        // return $desc[$image];

        $return = '';
        if ($desc[$image]) {
            $return .= $desc[$image];
        }

        if ($viewCamInfo and $model[$image]) {

            $return .= '<i>';
            if ($viewDateInfo) {
                $return .= $timestamp[$image] .' - ';
            }
            $return .= $model[$image] . '<br>';
            if ($iso[$image]) {
                $return .= 'ISO ' . $iso[$image] . ', ';
            }
            $return .= $foclen[$image] . ' ';
            $return .= $shutter[$image] . ' ';
            $return .= $aperture[$image];
            if ($flash[$image]) {
                $return .= ' ('
                        . $mig_messages[$mig_language]['flash_used']
                        . ')';
            }
        }

        return $return;

    } else {
        return '';
    }

}   // -- End of getExifDescription()



//  parseExifDate() - parses an EXIF date string and returns it in a
//  more human-readable format.

function parseExifDate ($stamp)
{
    // Use global language settings
    global $mig_messages;
    global $mig_language;

    // Separate into a date and a time
    list($date,$time) = split(' ', $stamp);

    // Parse date
    list($year, $month, $day) = split(':', $date);
    // Turn numeric month into a 3-character month string
    $month = $mig_messages[$mig_language]['month'][$month];
    $date = $month .' '. $day .' '. $year;

    // Parse time
    list($hour, $minute, $second) = split(':', $time);

    // Translate into 12-hour time
    switch ($hour) {
        case '00':
            $time = '12:' .$minute. 'AM';
            break;
        case '01':
        case '02':
        case '03':
        case '04':
        case '05':
        case '06':
        case '07':
        case '08':
        case '09':
        case '10':
        case '11':
            $time = $hour .':'. $minute .'AM';
            break;
        case '12':
            $time = $hour .':'. $minute . 'PM';
            break;
        case '13':
        case '14':
        case '15':
        case '16':
        case '17':
        case '18':
        case '19':
        case '20':
        case '21':
        case '22':
        case '23':
            $time = ($hour - 12) .':'. $minute . 'PM';
            break;
    }

    // Re-join before returning so it's one string
    $stamp = $date .', '. $time;

    return ($stamp);

}   // -- End of parseExifDate()



// getNewCurrDir() - replaces the silly old $newCurrDir being all
// over the place.  Especially in the URI string itself.

function getNewCurrDir( $currDir )
{

    // This just rips off the leading './' off currDir if it exists
    $newCurrDir = ereg_replace('^\.\/', '', $currDir);
    $newCurrDir = migURLencode($newCurrDir);
    return $newCurrDir;

}   // -- End of getNewCurrDir()



// getNumberOfImages() - counts images in a given folder

function getNumberOfImages( $folder, $useThumbSubdir, $markerType,
                            $markerLabel )
{

    $dir = opendir($folder);    // Open directory handle

    while ($file = readdir($dir)) {
        // Skip over thumbnails
        if (!$useThumbSubdir) {  // unless $useThumbSubdir is set,
                                 // then don't waste time on this check

            if ($markerType == 'suffix'
                and eregi("_$markerLabel\.(gif|jpg|png|jpeg|jpe)$", $file)) {
                    continue;
            }
            if ($markerType == 'prefix' and ereg("^$markerLabel\_", $file)) {
                continue;
            }
        }

        // We'll look at this one only if it's a file and it matches our list
        // of approved extensions
        $ext = getFileExtension($file);
        if (is_file("$folder/$file")
            and eregi('^(jpg|gif|png|jpeg|jpe)$', $ext)) {
                $count++;
        }
    }

    return $count;

}   // -- End of getNumberOfImages()



// migURLencode() - fixes a problem where "/" turns into "%2F" when
// using rawurlencode()

function migURLencode( $string )
{

    $new = $string;
    $new = rawurldecode($new);      // decode first
    $new = rawurlencode($new);      // then encode

    $new = str_replace('%2F', '/', $new);       // slash (/)

    return $new;

}   // -- End of migURLencode()



// folderFrame() - frames stuff in HTML table code... avoids template
// problems in places where there are images but no folders, or vice
// versa.

function folderFrame( $input )
{

    $retval = '<table border="0" cellpadding="2" cellspacing="0">'
            . '<tr><td class="folder">' . $input . '</td></tr></table><br>';

    return $retval;

}   // -- End of folderFrame()



// descriptionFrame() - Same thing as folderFrame() for descriptions.

function descriptionFrame( $input )
{

    $retval = '<table border="0" cellpadding="10" width="60%">'
            . '<tr><td class="desc">' . $input . '</td></tr></table><br>';

    return $retval;

}   // -- End of descriptionFrame()



// imageFrame() - Same thing as folderFrame() but for image tables.

function imageFrame( $input )
{

    $retval = '<table border="0" cellpadding="5" cellspacing="0"'
            . ' class="image"><tr><td>' . $input . '</td></tr></table><br>';

    return $retval;

}   // -- End of imageFrame()


//
// END OF FUNCTION DEFINITIONS
//
// ======================================================================
//
// MAIN CODE BODY FOLLOWS
//


// URL to use to call myself again
if ($PHP_SELF) {    // if using register_globals
    $baseURL = $PHP_SELF;
} else {            // otherwise, must be using track_vars
    $baseURL = $HTTP_SERVER_VARS['PHP_SELF'];
}

// base directory of installation
if ($PATH_TRANSLATED) {   // if using register_glolals
    $baseDir = dirname($PATH_TRANSLATED);
} else {                  // otherwise, must be using track_vars
    $baseDir = dirname($HTTP_SERVER_VARS['PATH_TRANSLATED']);
}

$configFile = $baseDir . '/config.php';             // Configuration file
$defaultConfigFile = $configFile . '.default';      // Default config file
// (used if $configFile does not exist)

// Collect the server name if possible
if ($SERVER_SOFTWARE) {
    $server = $SERVER_SOFTWARE;
} else {
    $server = $HTTP_SERVER_VARS['SERVER_SOFTWARE'];
}

// Fetch variables from the URI
//
if (! $currDir) {       // not using register_globals, so the assumption
                        // is that track_vars is in use
    $currDir        = $HTTP_GET_VARS['currDir'];
    $image          = $HTTP_GET_VARS['image'];
    $pageType       = $HTTP_GET_VARS['pageType'];
}
if (! $jump) {
    $jump           = $HTTP_GET_VARS['jump'];       // for track_vars
}

if ($currDir == '') {
    // Set a current directory if one doesn't exist
    $currDir = '.';
} else {
    // If there is one present, strip URL encoding from it
    $currDir = rawurldecode($currDir);
}

// Read configuration file
if (file_exists($configFile)) {
    $realConfig = $configFile;
} else {
    $realConfig = $defaultConfigFile;
}
if (file_exists($realConfig)) {
    include($realConfig);
} else {
    print "FATAL ERROR: Configuration file missing!";
    exit;
}

// Change $baseDir for PHP-Nuke compatibility mode
if ($phpNukeCompatible) {
    $baseDir .= '/mig';
}

// Backward compatibility with older config.php/mig.cfg versions
if ($maxColumns) {
    $maxThumbColumns = $maxColumns;
}

// Get rid of \'s if magic_quotes_gpc is turned on (causes problems).
if (get_magic_quotes_gpc() == 1) {
    $currDir = stripslashes($currDir);
    if ($image) {
        $image = stripslashes($image);
    }
}

// Turn off magic_quotes_runtime (causes trouble with some installations)
set_magic_quotes_runtime(0);

// Handle any password authentication needs

$workCopy = $currDir;       // temporary copy of $currDir

while ($workCopy) {

    if ($protect[$workCopy]) {

        // Try to get around the track_vars/register_globals problem
        if (! $PHP_AUTH_USER) {
            $PHP_AUTH_USER = $HTTP_SERVER_VARS['PHP_AUTH_USER'];
        }
        if (! $PHP_AUTH_PW) {
            $PHP_AUTH_PW = $HTTP_SERVER_VARS['PHP_AUTH_PW'];
        }

        // If there's not a username yet, fetch one by popping up a
        // login dialog box
        if (! $PHP_AUTH_USER) {
            header('WWW-Authenticate: Basic realm="protected"');
            header('HTTP/1.0 401 Unauthorized');
            print $mig_messages[$mig_language]['must_auth'];
            exit;

        } else {
            // Case #2: password/user are present but don't match up
            // with our known user base.  Reject the attempt.
            if ( crypt($PHP_AUTH_PW,
                       substr($protect[$workCopy][$PHP_AUTH_USER],0,2))
                 != $protect[$workCopy][$PHP_AUTH_USER] )
            {
                header('WWW-Authenticate: Basic realm="protected"');
                header('HTTP/1.0 401 Unauthorized');
                print $mig_messages[$mig_language]['must_auth'];
                exit;
            }
        }
        break;      // Since we had a match let's stop this loop
    }

    // if $workCopy is already down to '.' just nullify to end loop
    if ($workCopy == '.') {
        $workCopy = FALSE;
    } else {
        // pare $workCopy down one directory at a time
        // so we can check back all the way to '.'
        $workCopy = ereg_replace('/[^/]+$', '', $workCopy);
    }
}

$albumDir = $baseDir . '/albums';           // Where albums live
// If you change the directory here also make sure to change $albumURLroot

$templateDir = $baseDir . '/templates';     // Where templates live

// $baseURL with the scriptname torn off the end
$baseHref = ereg_replace('/[^/]+$', '', $baseURL);

// Change $baseHref for PHP-Nuke compatibility mode
if ($phpNukeCompatible) {
    $baseHref .= '/mig';
}

// Location of image library (for instance, where icons are kept)
$imageDir = $baseHref . '/images';

// Root where album images are living
$albumURLroot = $baseHref . '/albums';
// NOTE: Sometimes Windows users have to set this manually, like:
// $albumURLroot = '/mig/albums';

// Well, GIGO... set default to sane if someone screws up their
// config file
if ($markerType != 'prefix' and $markerType != 'suffix') {
    $markerType='suffix';
}
if (! $markerLabel) {
    $markerLabel = 'th';
}

// (Try to) get around the track_vars vs. register_globals problem
if (!$SERVER_NAME) {
    $SERVER_NAME = $HTTP_SERVER_VARS['SERVER_NAME'];
    $PATH_INFO = $HTTP_SERVER_VARS['PATH_INFO'];
}

// Is this a jump-tag URL?
if ($jump and $jumpMap[$jump] and $SERVER_NAME) {
    header("Location: http://$SERVER_NAME$baseURL?$jumpMap[$jump]");
    exit;
}

// Jump-tag using PATH_INFO rather than "....?jump=x" URI
if ($PATH_INFO and $jumpMap[$PATH_INFO] and $SERVER_NAME) {
    header("Location: http://$SERVER_NAME$baseURL?$jumpMap[$PATH_INFO]");
    exit;
}

// Is this a phpNuke compatible site?
if ($phpNukeCompatible) {

    // Bail out if the root directory isn't set.
    if (! $phpNukeRoot) {
        print "FATAL ERROR: phpNuke Root Directory is not set.";
        exit;
    }

    if (! isset($mainfile)) {
        include('mainfile.php');        // PHP-Nuke library
    }

    include('header.php');              // PHP-Nuke library

    // A table to nest Mig in, inside the PHPNuke framework
    print '<table width="100%" border="0" cellspacing="0" cellpadding="2"'
        . ' bgcolor="#000000"><tr><td>'
        . '<table width="100%" border="0" cellspacing="1" cellpadding="7"'
        . ' bgcolor="#FFFFFF"><tr><td>';
}

// Look at $currDir from a security angle.  Don't let folks go outside
// the album directory base
// if (ereg('\.\.', $currDir)) {
if (strstr($currDir, '..')) {
    print "SECURITY VIOLATION";
    exit;
}

// strip URL encoding here too
$image = rawurldecode($image);

// Fetch mig.cf information
list($hidden, $presort_dir, $presort_img, $desc, $bulletin, $ficons,
     $folderTemplate, $folderPageTitle, $folderFolderCols, $folderThumbCols,
     $folderMaintAddr)
    = parseMigCf("$albumDir/$currDir", $useThumbSubdir, $thumbSubdir);

// if $pageType is null, or "folder") generate a folder view

if ($pageType == 'folder' or $pageType == '') {

    // Determine which template to use
    if ($folderTemplate) {
        $templateFile = $folderTemplate;
    } elseif ($phpNukeCompatible) {
        $templateFile = $templateDir . '/mig_folder.php';
    } else {
        $templateFile = $templateDir . '/folder.html';
    }

    // Determine page title to use
    if ($folderPageTitle) {
        $pageTitle = $folderPageTitle;
    }

    // Set per-folder $maintAddr if one was defined
    if ($folderMaintAddr) {
        $maintAddr = $folderMaintAddr;
    }

    // Determine columns to use
    if ($folderFolderCols) {
        $maxFolderColumns = $folderFolderCols;
    }
    if ($folderThumbCols) {
        $maxThumbColumns = $folderThumbCols;
    }

    // Generate some HTML to pass to the template printer

    // list of available folders
    $folderList = buildDirList($baseURL, $albumDir, $currDir, $imageDir,
                               $useThumbSubdir, $thumbSubdir,
                               $maxFolderColumns, $hidden, $presort_dir,
                               $viewFolderCount, $markerType, $markerLabel,
                               $ficons);
    // list of available images
    $imageList = buildImageList($baseURL, $baseDir, $albumDir, $currDir,
                                $albumURLroot, $maxThumbColumns, $folderList,
                                $markerType, $markerLabel, $suppressImageInfo,
                                $useThumbSubdir, $thumbSubdir, $noThumbs,
                                $thumbExt, $suppressAltTags, $sortType,
                                $hidden, $presort_img, $desc, $imagePopup,
                                $imagePopType, $commentFilePerImage);

    // Only frame the lists in table code when appropriate

    // no folders or images - print the "no contents" line
    if ($folderList == 'NULL' and $imageList == 'NULL') {
        $folderList = $mig_messages[$mig_language]['no_contents'];
        $folderList = folderFrame($folderList);
        $imageList = '';

    // images, no folders.  Frame the imagelist in a table
    } elseif ($folderList == 'NULL' and $imageList != 'NULL') {
        $folderList = '';
        $imageList = imageFrame($imageList);

    // folders but no images.  Frame the folderlist in a table
    } elseif ($imageList == 'NULL' and $folderList != 'NULL') {
        $imageList = '';
        $folderList = folderFrame($folderList);

    // We have folders and we have images, so frame both in tables.
    } else {
        $folderList = folderFrame($folderList);
        $imageList = imageFrame($imageList);
    }

    // We have a bulletin
    if ($bulletin != '') {
        $bulletin = descriptionFrame($bulletin);
    }

    // build the "back" link
    $backLink = buildBackLink($baseURL, $currDir, 'back', $homeLink,
                              $homeLabel, $noThumbs);

    // build the "you are here" line
    $youAreHere = buildYouAreHere($baseURL, $currDir, '');

    // newcurrdir is currdir without the leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // parse the template file and print to stdout
    printTemplate($baseURL, $templateDir, $templateFile, $version, $maintAddr,
                  $folderList, $imageList, $backLink, '', '', '', $newCurrDir,
                  $pageTitle, '', '', '', $bulletin, $youAreHere, $distURL,
                  $albumDir, $server, $useVirtual);


// If $pageType is "image", show an image

} elseif ($pageType == 'image') {

    // Set per-foler page title if one was defined
    if ($folderPageTitle) {
        $pageTitle = $folderPageTitle;
    }

    // Set per-folder maintAddr if one was defined
    if ($folderMaintAddr) {
        $maintAddr = $folderMaintAddr;
    }

    // Trick the back link into going to the right place by adding
    // a bogus directory at the end
    $backLink = buildBackLink($baseURL, "$currDir/blah", 'up', '', '',
                              $noThumbs);

    // Get the "next image" and "previous image" links, and the current
    // position (#x of y)
    $Links = array ();
    $Links = buildNextPrevLinks($baseURL, $albumDir, $currDir, $image,
                                $markerType, $markerLabel, $hidden,
                                $presort_img, $sortType);
    list($nextLink, $prevLink, $currPos) = $Links;

    // Get image description
    if ($commentFilePerImage) {
        $description  = getImageDescFromFile($image, $albumDir, $currDir);
    } else {
        $description  = getImageDescription($image, $desc);
    }
    $exifDescription = getExifDescription($albumDir, $currDir, $image,
                                          $viewCamInfo, $viewDateInfo);

    // If there's a description but no exifDescription, just make the
    // exifDescription the description
    if ($exifDescription and ! $description) {
        $description = $exifDescription;
        unset($exifDescription);
    }

    // If both descriptions are non-NULL, separate them with an <HR>
    if ($description and $exifDescription) {
        $description .= '<hr>';
        $description .= $exifDescription;
    }

    // If there's a description at all, frame it in a table.
    if ($description != '') {
        $description = descriptionFrame($description);
    }

    // Build the "you are here" line
    $youAreHere = buildYouAreHere($baseURL, $currDir, $image);

    // Determine what template to use, based on what mode we are in
    if ($phpNukeCompatible) {
        $templateFile = $templateDir . '/mig_image.php';
    } else {
        $templateFile = $templateDir . '/image.html';
    }

    // newcurrdir is currdir without the leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // Send it all to the template printer to dump to stdout
    printTemplate($baseURL, $templateDir, $templateFile, $version, $maintAddr,
                  '', '', $backLink, $albumURLroot, $image, $currDir,
                  $newCurrDir, $pageTitle, $prevLink, $nextLink, $currPos,
                  $description, $youAreHere, $distURL, $albumDir, $server,
                  $useVirtual);
}

// If in PHPNuke mode, finish up the tables and such needed for PHPNuke
if ($phpNukeCompatible) {
    print '</table></center></td></tr></table>';
    include('footer.php');
}

?>
