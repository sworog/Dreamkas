package project.lighthouse.autotests.steps;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.apache.commons.io.FileUtils;
import org.joda.time.DateTime;
import org.joda.time.format.DateTimeFormat;
import org.joda.time.format.DateTimeFormatter;
import project.lighthouse.autotests.console.ConsoleCommand;
import project.lighthouse.autotests.console.ConsoleCommandResult;
import project.lighthouse.autotests.robotClient.InterruptedException_Exception;
import project.lighthouse.autotests.robotClient.SetRobotHubWS;
import project.lighthouse.autotests.robotClient.SetRobotHubWSService;

import java.io.File;
import java.io.IOException;

import static junit.framework.Assert.fail;

public class RobotSteps extends ScenarioSteps {

    SetRobotHubWS robotPort = new SetRobotHubWSService().getSetRobotHubWSPort();

    private static final String SERVER_URL = "//faro.lighthouse.cs";
    private static final String IMPORT_FOLDER_PATH = "/centrum/reports";

    public RobotSteps(Pages pages) {
        super(pages);
    }

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
        final String sourceFolder = "//faro.lighthouse.cs/centrum/products/source";
        final String tmpFolder = "//faro.lighthouse.cs/centrum/products/tmp";
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

    @Step
    public void runConsoleCommand(String command, String folder) throws IOException, InterruptedException {
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand(folder, System.getProperty("init")).exec(command);
        if (!consoleCommandResult.isOk()) {
            Assert.fail(consoleCommandResult.getOutput());
        }
    }
}
