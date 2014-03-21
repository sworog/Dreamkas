package project.lighthouse.autotests.helper;

import org.apache.commons.io.FileUtils;

import java.io.File;
import java.io.IOException;
import java.net.URL;

/**
 * Class to handle file downloading
 */
public class FileDownloader {

    private String urlString;
    private String fileName;
    private File file;

    public FileDownloader(String urlString, String fileName) throws IOException {
        this.urlString = urlString;
        this.fileName = fileName;
        downloadFile();
    }

    private void downloadFile() throws IOException {
        if (urlString.trim().equals("")) {
            throw new AssertionError("The element you have specified does not link to anything!");
        }
        String downloadPath = System.getProperty("java.io.tmpdir");
        URL downloadURL = new URL(urlString);
        File downloadedFile = new File(downloadPath + "/" + fileName);
        FileUtils.copyURLToFile(downloadURL, new File(downloadPath + "/" + fileName));
        this.file = downloadedFile;
    }

    public File getFile() {
        return file;
    }
}
