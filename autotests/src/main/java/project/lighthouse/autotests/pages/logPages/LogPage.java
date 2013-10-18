package project.lighthouse.autotests.pages.logPages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.log.SimpleLogObject;

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

    public List<SimpleLogObject> getSimpleLogMessages() {
        List<SimpleLogObject> logMessages = new ArrayList<>();
        for (WebElement logMessageWebElement : getSimpleLogMessageWebElements()) {
            SimpleLogObject simpleLogObject = new SimpleLogObject(getDriver(), logMessageWebElement);
            logMessages.add(simpleLogObject);
        }
        return logMessages;
    }

    public SimpleLogObject getLastLogObject() {
        return getSimpleLogMessages().get(0);
    }
}
