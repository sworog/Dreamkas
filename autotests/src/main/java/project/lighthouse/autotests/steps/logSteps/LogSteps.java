package project.lighthouse.autotests.steps.logSteps;

import junit.framework.Assert;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.logPage.LogPage;

public class LogSteps extends ScenarioSteps {

    LogPage logPage;

    public LogSteps(Pages pages) {
        super(pages);
    }

    public void open() {
        logPage.open();
    }

    public String getLastLogStatus() {
        return logPage.getLastRecalcProductLogMessage().getStatus();
    }

    public String getLastLogStatusText() {
        return logPage.getLastRecalcProductLogMessage().getStatusText();
    }

    public String getLastLogProduct() {
        return logPage.getLastRecalcProductLogMessage().getProduct();
    }

    public String getLastLogTitle() {
        return logPage.getLastRecalcProductLogMessage().getTitle();
    }

    public void waitStatusForSuccess() {
        String status = getLastLogStatus();
        int retriesCount = 0;
        while (!status.equals("success") && retriesCount < 10) {
            status = getLastLogStatus();
            getDriver().navigate().refresh();
            retriesCount++;
        }
    }

    public void assertLastLogProduct(String expectedMessage) {
        Assert.assertEquals(expectedMessage, getLastLogProduct());
    }

    public void assertLastLogTitle(String expectedTitle) {
        Assert.assertEquals(expectedTitle, getLastLogTitle());
    }

    public void assertLastLogStatusText(String expectedStatusText) {
        Assert.assertEquals(expectedStatusText, getLastLogStatusText());
    }
}
