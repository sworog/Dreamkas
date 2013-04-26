<?php

if (empty($_SERVER['argv'][1])) {
    echo 'Missing argument - clover report file';
    exit(1);
}

$file = $_SERVER['argv'][1];

if (!is_file($file)) {
    echo 'Invalid argument - argument should be a file.';
    exit(1);
}

if (!is_readable($file)) {
    echo 'Invalid argument - Unable to read file.';
    exit(1);
}

$sxml = simplexml_load_file($file);

$metricsNode = reset($sxml->xpath('/coverage/project/metrics'));

$metrics = array(
    'CodeCoverageAbsMTotal'     => (string) $metricsNode['methods'],
    'CodeCoverageAbsMCovered'   => (string) $metricsNode['coveredmethods'],
    'CodeCoverageM'             => percent((string) $metricsNode['coveredmethods'], (string) $metricsNode['methods']),
    'CodeCoverageAbsLTotal'     => (string) $metricsNode['statements'],
    'CodeCoverageAbsLCovered'   => (string) $metricsNode['coveredstatements'],
    'CodeCoverageL'             => percent((string) $metricsNode['coveredstatements'], (string) $metricsNode['statements']),
    'LOC'                       => (string) $metricsNode['loc'],
    'NCLOC'                     => (string) $metricsNode['ncloc'],
);

// Workaround to calculate covered classes
$classesCovered = 0;
foreach ($sxml->xpath('//class/metrics') as $classMetrics) {
    if ((string) $classMetrics['statements'] == (string) $classMetrics['coveredstatements']) {
        $classesCovered++;
    }
}

$metrics['CodeCoverageAbsCCovered'] = $classesCovered;
$metrics['CodeCoverageAbsCTotal']   = (string) $metricsNode['classes'];
$metrics['CodeCoverageC'] = percent($classesCovered, (string) $metricsNode['classes']);

// print all messages for custom graphs
foreach ($metrics as $key => $value) {
    echo "##teamcity[buildStatisticValue key='" . $key . "' value='" . $value . "']\n";
}

/**
 * @param int $covered
 * @param int $total
 * @return string
 */
function percent($covered, $total)
{
    if (0 == $total && 0 == $covered) {
        $percent = 100;
    } elseif (0 == $total) {
        $percent = 0;
    } else {
        $percent = ($covered / $total) * 100;
    }
    return sprintf("%.2f", $percent);
}
