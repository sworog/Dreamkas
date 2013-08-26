package project.lighthouse.autotests.pages.logPage;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.LogObject;

import java.util.ArrayList;
import java.util.List;

@DefaultUrl("/jobs")
public class LogPage extends CommonPageObject {

    private static final String RECALC_PRODUCT_MESSAGE_TYPE = "recalc_product_price";

    public LogPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public List<WebElement> getLogMessageWebElements() {
        return waiter.getPresentWebElements(By.xpath("//*[@class='jobs__item']"));
    }

    public List<LogObject> getLogMessages() {
        List<WebElement> logMessageWebElements = getLogMessageWebElements();
        List<LogObject> logMessages = new ArrayList<>();
        for (WebElement logMessageWebElement : logMessageWebElements) {
            String id = logMessageWebElement.getAttribute("id");
            String type = logMessageWebElement.getAttribute("type");
            String status = logMessageWebElement.getAttribute("status");
            String title = logMessageWebElement.findElement(By.xpath("//*[@class='jobs__title']")).getText();
            String product = logMessageWebElement.findElement(By.xpath("//*[@class='jobs__productName']")).getText();
            String statusText = logMessageWebElement.findElement(By.xpath("//*[@class='jobs__status']")).getText();
            LogObject logObject = new LogObject(id, type, status, title, product, statusText);
            logMessages.add(logObject);
        }
        return logMessages;
    }

    public List<LogObject> getLogMessagesByType(String type) {
        List<LogObject> logMessages = getLogMessages();
        List<LogObject> logMessagesByType = new ArrayList<>();
        for (LogObject logMessage : logMessages) {
            if (logMessage.getType().equals(type)) {
                logMessagesByType.add(logMessage);
            }
        }
        return logMessagesByType;
    }

    public LogObject getLastLogMessageByType(String type) {
        List<LogObject> logMessagesByType = getLogMessagesByType(type);
        return logMessagesByType.get(0);
    }

    public LogObject getLastRecalcProductLogMessage() {
        return getLastLogMessageByType(RECALC_PRODUCT_MESSAGE_TYPE);
    }
}
