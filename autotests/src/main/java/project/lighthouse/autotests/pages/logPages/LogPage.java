package project.lighthouse.autotests.pages.logPages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.SimpleLogObject;

import java.util.ArrayList;
import java.util.List;

public class LogPage extends CommonPageObject {

    public LogPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    private List<WebElement> getSimpleLogMessageWebElements() {
        return waiter.getVisibleWebElements(By.xpath("//*[@class='log__item']"));
    }

    private List<SimpleLogObject> getSimpleLogMessages() {
        List<WebElement> logMessageWebElements = getSimpleLogMessageWebElements();
        List<SimpleLogObject> logMessages = new ArrayList<>();
        for (WebElement logMessageWebElement : logMessageWebElements) {
            String message = logMessageWebElement.findElement(By.xpath("//*[@class='log__finalMessage']")).getText();
            SimpleLogObject simpleLogObject = new SimpleLogObject(message);
            logMessages.add(simpleLogObject);
        }
        return logMessages;
    }

    public SimpleLogObject getLastLogObject() {
        return getSimpleLogMessages().get(0);
    }
}
