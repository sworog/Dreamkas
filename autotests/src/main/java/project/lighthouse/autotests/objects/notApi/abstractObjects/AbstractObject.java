package project.lighthouse.autotests.objects.notApi.abstractObjects;

import org.openqa.selenium.WebElement;

import java.util.Map;

abstract public class AbstractObject {

    public WebElement element;

    public AbstractObject(WebElement element) {
        this.element = element;
        setProperties();
    }

    abstract public void setProperties();

    public void click() {
        element.click();
    }

    abstract public Boolean rowIsEqual(Map<String, String> row);

    abstract public String getObjectLocator();
}
