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
    'codeCoverageMethodsTotal'      => (string) $metricsNode['methods'],
    'codeCoverageMethodsCovered'    => (string) $metricsNode['coveredmethods'],
    'codeCoverageMethodsPercent'    => percent((string) $metricsNode['coveredmethods'], (string) $metricsNode['methods']),
    'codeCoverageLinesTotal'        => (string) $metricsNode['statements'],
    'codeCoverageLinesCovered'      => (string) $metricsNode['coveredstatements'],
    'codeCoverageLinesPercent'      => percent((string) $metricsNode['coveredstatements'], (string) $metricsNode['statements']),
    'codeCoverageLinesLOC'          => (string) $metricsNode['loc'],
    'codeCoverageLinesNCLOC'        => (string) $metricsNode['ncloc'],
);

// Workaround to calculate covered classes
$classesCovered = 0;
foreach ($sxml->xpath('//class') as $class) {
    foreach ($class->xpath('metrics') as $classMetrics) {
        if ((string) $classMetrics['statements'] == (string) $classMetrics['coveredstatements']) {
            $classesCovered++;
        }
    }
}

$metrics['codeCoverageClassesCovered'] = $classesCovered;
$metrics['codeCoverageClassesTotal']   = (string) $metricsNode['classes'];
$metrics['codeCoverageClassesPercent'] = percent($classesCovered, (string) $metricsNode['classes']);

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
