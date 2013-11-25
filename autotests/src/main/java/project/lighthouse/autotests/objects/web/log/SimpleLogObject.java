package project.lighthouse.autotests.objects.web.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;

import java.util.Map;

public class SimpleLogObject extends AbstractObjectNode {

    private String message;

    public SimpleLogObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        message = setProperty(By.xpath(".//*[@class='log__finalMessage']"));
    }

    public Boolean rowContains(Map<String, String> row) {
        return message.contains(row.get("logMessage"));
    }
}
