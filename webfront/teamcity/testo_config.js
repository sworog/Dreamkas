var programFiles= process.env['ProgramFiles(x86)'] || process.env.ProgramFiles

module.exports= {
    uri: 'http://localhost:8008/lh/-mix/index.stage=release.doc.xml?testo_session={testo_session}',
    timeout: 10000,
    browsers: [
        programFiles + '/Mozilla Firefox/firefox.exe',
        programFiles + '/Opera/opera.exe',
        //programFiles + '/Internet Explorer/iexplore.exe',
        programFiles + '/Google/Chrome/Application/chrome.exe',
    ],
    host: 'localhost',
    port: 1351,
}


