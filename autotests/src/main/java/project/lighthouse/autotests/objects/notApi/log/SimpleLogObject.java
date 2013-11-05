package project.lighthouse.autotests.objects.notApi.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObjectNode;

import java.util.Map;

public class SimpleLogObject extends AbstractObjectNode {

    String message;

    public SimpleLogObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        message = getElement().findElement(By.xpath(".//*[@class='log__finalMessage']")).getText();
    }

    public Boolean rowContains(Map<String, String> row) {
        return message.contains(row.get("logMessage"));
    }
}
