package project.lighthouse.autotests.pages.logPages;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.log.JobLogObject;

import java.util.ArrayList;
import java.util.List;

@DefaultUrl("/logs")
public class JobsPage extends CommonPageObject {

    public static final String RECALC_PRODUCT_MESSAGE_TYPE = "recalc_product_price";
    public static final String SET10_EXPORT_PRODUCTS_TYPE = "set10_export_products";
    public static final String SUCCESS_STATUS = "success";

    public JobsPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    private List<WebElement> getJobLogMessageWebElements() {
        return waiter.getVisibleWebElements(By.xpath("//*[@class='jobs__item']"));
    }

    private List<JobLogObject> getJobLogMessages() {
        List<WebElement> logMessageWebElements = getJobLogMessageWebElements();
        List<JobLogObject> logMessages = new ArrayList<>();
        for (WebElement logMessageWebElement : logMessageWebElements) {
            JobLogObject jobLogObject = new JobLogObject(getDriver(), logMessageWebElement);
            logMessages.add(jobLogObject);
        }
        return logMessages;
    }

    private List<JobLogObject> getJobLogMessagesByType(String type) {
        List<JobLogObject> logMessages = getJobLogMessages();
        List<JobLogObject> logMessagesByType = new ArrayList<>();
        for (JobLogObject logMessage : logMessages) {
            if (logMessage.getType().equals(type)) {
                logMessagesByType.add(logMessage);
            }
        }
        return logMessagesByType;
    }

    private JobLogObject getLastJobLogMessageByType(String type) {
        List<JobLogObject> logMessagesByType = getJobLogMessagesByType(type);
        return logMessagesByType.get(0);
    }

    public JobLogObject getLastRecalcProductLogMessage() {
        return getLastJobLogMessageByType(RECALC_PRODUCT_MESSAGE_TYPE);
    }

    public JobLogObject getLastExportLogMessage() {
        return getLastJobLogMessageByType(SET10_EXPORT_PRODUCTS_TYPE);
    }
}
