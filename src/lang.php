<?php // $Revision$

//
// lang.php - Language library for MiG
//
// Copyright (c) 2000-2001 Dan Lowe <dan@tangledhelix.com>
//     http://mig.sourceforge.net/
//
//
// Currently available:
//
//  en      English
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

?>
