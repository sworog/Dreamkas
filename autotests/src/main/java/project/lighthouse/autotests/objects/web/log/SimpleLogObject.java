package project.lighthouse.autotests.objects.web.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;

import java.util.Map;

public class SimpleLogObject extends AbstractObjectNode {

    String message;

    public SimpleLogObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        Waiter waiter = new Waiter(getWebDriver(), 0);
        if (!waiter.invisibilityOfElementLocated(By.xpath(".//*[@class='log__finalMessage']"))) {
            message = getElement().findElement(By.xpath(".//*[@class='log__finalMessage']")).getText();
        }
    }

    public Boolean rowContains(Map<String, String> row) {
        return message.contains(row.get("logMessage"));
    }
}
