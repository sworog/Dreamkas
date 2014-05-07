package project.lighthouse.autotests.steps;

import jcifs.smb.NtlmPasswordAuthentication;
import jcifs.smb.SmbException;
import jcifs.smb.SmbFile;
import jcifs.smb.SmbFileInputStream;
import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.apache.commons.io.FileUtils;
import org.apache.commons.io.IOUtils;
import org.custommonkey.xmlunit.Diff;
import org.joda.time.DateTime;
import org.joda.time.format.DateTimeFormat;
import org.joda.time.format.DateTimeFormatter;
import org.xml.sax.SAXException;
import project.lighthouse.autotests.robotClient.InterruptedException_Exception;
import project.lighthouse.autotests.robotClient.SetRobotHubWS;
import project.lighthouse.autotests.robotClient.SetRobotHubWSService;

import java.io.File;
import java.io.IOException;
import java.net.MalformedURLException;
import java.net.UnknownHostException;
import java.nio.charset.Charset;

import static junit.framework.Assert.assertTrue;
import static junit.framework.Assert.fail;

public class RobotSteps extends ScenarioSteps {

    SetRobotHubWS robotPort = new SetRobotHubWSService().getSetRobotHubWSPort();

    private static final String SERVER_URL = System.getProperty("centrum.server.url");
    private static final String IMPORT_FOLDER_PATH = System.getProperty("centrum.import.folder.path");

    @Step
    public void prepareData(String fileName) throws IOException, InterruptedException {
        final String sourcePath = String.format("%s/xml/purchases/%s", System.getProperty("user.dir").replace("\\", "/"), fileName);
        final String destinationPath = getFolderPath(IMPORT_FOLDER_PATH) + getFileName();
        FileUtils.copyFile(new File(sourcePath), new File(destinationPath));
    }

    @Step
    public void checkImportIsDone() throws InterruptedException {
        final String importFolder = getFolderPath(IMPORT_FOLDER_PATH);
        checkFolderIsEmptyLoop(importFolder);
    }

    @Step
    public void checkExportIsDone() throws InterruptedException {
        final String sourceFolder = SERVER_URL + "/centrum/products/source";
        final String tmpFolder = SERVER_URL + "/centrum/products/tmp";
        checkFolderIsEmptyLoop(sourceFolder);
        checkFolderIsEmptyLoop(tmpFolder);
    }

    @Step
    public String runTest(String cashIp, String testName) throws InterruptedException_Exception {
        return robotPort.runTest(cashIp, testName);
    }

    @Step
    public String getStatus(String uuid) {
        return robotPort.status(uuid);
    }

    @Step
    public void waitForStatus(String uuid) {
        String status = getStatus(uuid);
        while (!status.startsWith("finished")) {
            status = getStatus(uuid);
        }
        if (!status.equals("finished;PASSED")) {
            Assert.fail(status);
        }
    }

    @Step
    public void checkProductWeightExport(String fixtureFile) {
        String directoryPath = SERVER_URL + "/centrum/autotests/source/";
//        File[] files = FileUtils.getFile(directoryPath).listFiles();
//        assert files != null;
//        Assert.assertTrue(files.length != 0);
//
//        File actualFile = files[0];
        String actualXml = getLastRemoteFileAsString(directoryPath);
        File expectedFile = new File(String.format("%s/xml/%s", System.getProperty("user.dir").replace("\\", "/"), fixtureFile));

        Diff diff = null;
        try {
            String expectedXml = FileUtils.readFileToString(expectedFile);
            diff = new Diff(expectedXml, actualXml);
        } catch (SAXException | IOException e) {
            e.printStackTrace();
        }

//        String actualFileName = actualFile.getName();
//        actualFile.delete();

        assert diff != null;
        assertTrue("Xml file not equals ", diff.similar());
    }

    private String getLastRemoteFileAsString(String directory) {
        directory = "smb:".concat(directory);
        NtlmPasswordAuthentication auth = new NtlmPasswordAuthentication("erp:erp");
        String fileText = null;
        SmbFileInputStream fileInputStream = null;
        try {
            SmbFile remoteDirectory = new SmbFile(directory, auth);
            SmbFile[] files = remoteDirectory.listFiles();
            assert files != null;
            Assert.assertTrue(files.length != 0);
            fileInputStream = new SmbFileInputStream(files[files.length - 1]);
            fileText = IOUtils.toString(fileInputStream, Charset.defaultCharset());
        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            if (fileInputStream != null) {
                try {
                    fileInputStream.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }

        return fileText;
    }

    private String getFileName() {
        DateTimeFormatter dtf = DateTimeFormat.forPattern("dd-MM-yyyy_HH-mm-ss");
        return String.format("purchases-%s.xml", dtf.print(DateTime.now()));
    }

    private Boolean isDirectoryEmpty(String directoryPath) {
        return FileUtils.getFile(directoryPath).list().length == 0;
    }

    private String getServerUrl() {
        return SERVER_URL;
    }

    private String getFolderPath(String folderPath) {
        return getServerUrl() + folderPath + "/";
    }

    private void checkFolderIsEmptyLoop(String folderPath) throws InterruptedException {
        Boolean folderIsEmpty = isDirectoryEmpty(folderPath);
        int count = 0;
        while (!folderIsEmpty && count < 61) {
            folderIsEmpty = isDirectoryEmpty(folderPath);
            Thread.sleep(1000);
            count++;
        }
        if (!folderIsEmpty && count == 61) {
            fail("The folder is still not empty after timeOut");
        }
    }
}
