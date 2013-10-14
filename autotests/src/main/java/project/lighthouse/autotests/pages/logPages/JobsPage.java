package project.lighthouse.autotests.pages.logPages;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.JobLogObject;

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
            String id = logMessageWebElement.getAttribute("id");
            String type = logMessageWebElement.getAttribute("type");
            String status = logMessageWebElement.getAttribute("status");
            String title = logMessageWebElement.findElement(By.xpath("//*[@class='jobs__title']")).getText();
            String product = null;
            if (isElementVisible(By.xpath("//*[@class='jobs__productName']"))) {
                product = logMessageWebElement.findElement(By.xpath("//*[@class='jobs__productName']")).getText();
            }
            String statusText = logMessageWebElement.findElement(By.xpath("//*[@class='jobs__status']")).getText();
            JobLogObject jobLogObject = new JobLogObject(id, type, status, title, product, statusText);
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
