package project.lighthouse.autotests.pages.logPage;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.LogObject;

import java.util.ArrayList;
import java.util.List;

@DefaultUrl("/logPage")
public class LogPage extends CommonPageObject {

    public LogPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public List<WebElement> getLogMessageWebElements() {
        return waiter.getPresentWebElements(By.xpath("//*[@name='logMessage']"));
    }

    public List<LogObject> getLogMessages() {
        List<WebElement> logMessageWebElements = getLogMessageWebElements();
        List<LogObject> logMessages = new ArrayList<>();
        for (WebElement logMessageWebElement : logMessageWebElements) {
            String id = logMessageWebElement.findElement(By.xpath("id")).getText();
            String status = logMessageWebElement.findElement(By.xpath("status")).getText();
            String title = logMessageWebElement.findElement(By.xpath("title")).getText();
            String finalMessage = logMessageWebElement.findElement(By.xpath("finalMessage")).getText();
            LogObject logObject = new LogObject(id, status, title, finalMessage);
            logMessages.add(logObject);
        }
        return logMessages;
    }

    public LogObject getLastLogMessage() {
        List<LogObject> logMessages = getLogMessages();
        return logMessages.get(logMessages.size() - 1);
    }
}
