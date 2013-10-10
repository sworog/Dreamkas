package project.lighthouse.autotests.steps.logSteps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.logPages.JobsPage;
import project.lighthouse.autotests.pages.logPages.LogPage;

import static junit.framework.Assert.assertEquals;
import static junit.framework.Assert.fail;

public class LogSteps extends ScenarioSteps {

    JobsPage jobsPage;
    LogPage logPage;


    public LogSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void open() {
        jobsPage.open();
    }

    @Step
    public void waitLastRecalcLogMessageSuccessStatus() {
        waitStatusForSuccess(JobsPage.RECALC_PRODUCT_MESSAGE_TYPE);
    }

    @Step
    public void waitLastSet10ExportProductLogMessageSuccessStatus() {
        waitStatusForSuccess(JobsPage.SET10_EXPORT_PRODUCTS_TYPE);
    }

    @Step
    public void waitStatusForSuccess(String logType) {
        String status = getStatusByType(logType);
        int retriesCount = 0;
        while (!status.equals(JobsPage.SUCCESS_STATUS) && retriesCount < 10) {
            status = getStatusByType(logType);
            getDriver().navigate().refresh();
            retriesCount++;
        }
        assertEquals(JobsPage.SUCCESS_STATUS, status);
    }

    public String getStatusByType(String logType) {
        switch (logType) {
            case JobsPage.RECALC_PRODUCT_MESSAGE_TYPE:
                return jobsPage.getLastRecalcProductLogMessage().getStatus();
            case JobsPage.SET10_EXPORT_PRODUCTS_TYPE:
                return jobsPage.getLastExportLogMessage().getStatus();
            default:
                fail(
                        String.format("No such option '%s'", logType)
                );
        }
        return "";
    }

    @Step
    public void assertLastRecalcLogProduct(String expectedMessage) {
        assertEquals(expectedMessage, jobsPage.getLastRecalcProductLogMessage().getProduct());
    }

    @Step
    public void assertLastRecalcLogTitle(String expectedTitle) {
        assertEquals(expectedTitle, jobsPage.getLastRecalcProductLogMessage().getTitle());
    }

    @Step
    public void assertLastRecalcLogStatusText(String expectedStatusText) {
        assertEquals(expectedStatusText, jobsPage.getLastRecalcProductLogMessage().getStatusText());
    }

    @Step
    public void assertLastSet10ExportRecalcLogTitle(String expectedTitle) {
        assertEquals(expectedTitle, jobsPage.getLastExportLogMessage().getTitle());
    }

    @Step
    public void assertLastSet10ExportRecalcLogStatusText(String expectedStatusText) {
        assertEquals(expectedStatusText, jobsPage.getLastExportLogMessage().getStatusText());
    }

    @Step
    public void assertLastSimpleLogMessage(String expectedMessage) {
        assertEquals(logPage.getLastLogObject().getMessage(), expectedMessage);
    }
}
