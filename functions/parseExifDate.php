<?

// parseExifDate() - Parses an EXIF date string and returns it in a more human-readable format.

function parseExifDate ( $stamp )
{
    global $mig_config;

    // Separate into a date and a time
    list($date,$time) = split(' ', $stamp);

    // Parse date
    list($year, $month, $day) = split(':', $date);

    // Turn numeric month into a 3-character month string
    $month = $mig_config['lang']['month'][$month];

    // Parse time
    list($hour, $minute, $second) = split(':', $time);

    // Translate into 12-hour time
    switch ($hour) {
        case '00':
            $time = '12:' . $minute . $mig_config['lang']['am'];
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
            $time = $hour . ':' . $minute . $mig_config['lang']['am'];
            break;
        case '12':
            $time = $hour . ':' . $minute . $mig_config['lang']['pm'];
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
            $time = ($hour - 12) . ':' . $minute . $mig_config['lang']['pm'];
            break;
    }

    return array ( $year, $month, $day, $time );

}   // -- End of parseExifDate()

?>