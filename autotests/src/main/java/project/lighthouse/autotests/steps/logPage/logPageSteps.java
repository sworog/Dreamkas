package project.lighthouse.autotests.steps.logPage;

import junit.framework.Assert;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.logPage.LogPage;

public class LogPageSteps extends ScenarioSteps {

    LogPage logPage;

    public LogPageSteps(Pages pages) {
        super(pages);
    }

    public void open() {
        logPage.open();
    }

    public String getLastLogStatus() {
        return logPage.getLastRecalcProductLogMessage().getStatus();
    }

    public String getLastLogFinalMessage() {
        return logPage.getLastRecalcProductLogMessage().getFinalMessage();
    }

    public String getLastLogTitle() {
        return logPage.getLastRecalcProductLogMessage().getTitle();
    }

    public void waitStatusForSuccess() {
        String statusText = getLastLogStatus();
        while (!statusText.equals("Success")) {
            statusText = getLastLogStatus();
            getDriver().navigate().refresh();
        }
    }

    public void assertLastLogFinalMessage(String expectedMessage) {
        Assert.assertEquals(expectedMessage, getLastLogFinalMessage());
    }

    public void assertLastLogTitle(String expectedTitle) {
        Assert.assertEquals(expectedTitle, getLastLogTitle());
    }
}
