package project.lighthouse.autotests.steps;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.apache.commons.io.FileUtils;
import project.lighthouse.autotests.robotClient.InterruptedException_Exception;
import project.lighthouse.autotests.robotClient.SetRobotHubWS;
import project.lighthouse.autotests.robotClient.SetRobotHubWSService;

import java.io.File;
import java.io.IOException;

public class RobotSteps extends ScenarioSteps {

    SetRobotHubWS robotPort = new SetRobotHubWSService().getSetRobotHubWSPort();

    public RobotSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void prepareData() throws IOException, InterruptedException {
        final String sourcePath = String.format("%s/xml/catalog-goods_123.xml", System.getProperty("user.dir").replace("\\", "/"));
        final String destinationPath = "//faro.lighthouse.cs/centrum/products/source/catalog-goods_123.xml";
        FileUtils.copyFile(new File(sourcePath), new File(destinationPath));
        checkExportIsDone();
    }

    @Step
    public void checkExportIsDone() throws InterruptedException {
        final String sourceFolder = "//faro.lighthouse.cs/centrum/products/source";
        final String tmpFolder = "//faro.lighthouse.cs/centrum/products/tmp";
        Boolean sourceFolderIsEmpty = isDirectoryEmpty(sourceFolder);
        while (!sourceFolderIsEmpty) {
            sourceFolderIsEmpty = isDirectoryEmpty(sourceFolder);
            Thread.sleep(1000);
        }
        Boolean tmpFolderIsEmpty = isDirectoryEmpty(tmpFolder);
        while (!tmpFolderIsEmpty) {
            tmpFolderIsEmpty = isDirectoryEmpty(tmpFolder);
            Thread.sleep(1000);
        }
    }

    public Boolean isDirectoryEmpty(String directoryPath) {
        return FileUtils.getFile(directoryPath).list().length == 0;
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
}
