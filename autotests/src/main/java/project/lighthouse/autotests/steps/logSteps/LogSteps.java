package project.lighthouse.autotests.steps.logSteps;

import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.logPage.LogPage;

import static junit.framework.Assert.assertEquals;
import static junit.framework.Assert.fail;

public class LogSteps extends ScenarioSteps {

    LogPage logPage;

    public LogSteps(Pages pages) {
        super(pages);
    }

    public void open() {
        logPage.open();
    }

    public void waitLastRecalcLogMessageSuccessStatus() {
        waitStatusForSuccess(LogPage.RECALC_PRODUCT_MESSAGE_TYPE);
    }

    public void waitLastSet10ExportProductLogMessageSuccessStatus() {
        waitStatusForSuccess(LogPage.SET10_EXPORT_PRODUCTS_TYPE);
    }

    public void waitStatusForSuccess(String logType) {
        String status = getStatusByType(logType);
        int retriesCount = 0;
        while (!status.equals(LogPage.SUCCESS_STATUS) && retriesCount < 10) {
            status = getStatusByType(logType);
            getDriver().navigate().refresh();
            retriesCount++;
        }
        assertEquals(LogPage.SUCCESS_STATUS, status);
    }

    public String getStatusByType(String logType) {
        switch (logType) {
            case LogPage.RECALC_PRODUCT_MESSAGE_TYPE:
                return logPage.getLastRecalcProductLogMessage().getStatus();
            case LogPage.SET10_EXPORT_PRODUCTS_TYPE:
                return logPage.getLastExportLogMessage().getStatus();
            default:
                fail(
                        String.format("No such option '%s'", logType)
                );
        }
        return "";
    }

    public void assertLastRecalcLogProduct(String expectedMessage) {
        assertEquals(expectedMessage, logPage.getLastRecalcProductLogMessage().getProduct());
    }

    public void assertLastRecalcLogTitle(String expectedTitle) {
        assertEquals(expectedTitle, logPage.getLastRecalcProductLogMessage().getTitle());
    }

    public void assertLastRecalcLogStatusText(String expectedStatusText) {
        assertEquals(expectedStatusText, logPage.getLastRecalcProductLogMessage().getStatusText());
    }

    public void assertLastSet10ExportRecalcLogTitle(String expectedTitle) {
        assertEquals(expectedTitle, logPage.getLastExportLogMessage().getTitle());
    }

    public void assertLastSet10ExportRecalcLogStatusText(String expectedStatusText) {
        assertEquals(expectedStatusText, logPage.getLastExportLogMessage().getStatusText());
    }

}
