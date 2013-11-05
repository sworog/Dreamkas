package project.lighthouse.autotests.objects.notApi.abstractObjects;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

import java.util.Map;

public class AbstractObjectNode extends AbstractObject {

    public AbstractObjectNode(WebElement element) {
        super(element);
    }

    public AbstractObjectNode(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
    }

    @Override
    public Boolean rowIsEqual(Map<String, String> row) {
        return null;
    }

    @Override
    public String getObjectLocator() {
        return null;
    }
}
