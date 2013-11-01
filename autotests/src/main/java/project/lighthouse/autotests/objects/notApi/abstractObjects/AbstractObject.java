package project.lighthouse.autotests.objects.notApi.abstractObjects;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

import java.util.Map;

abstract public class AbstractObject {

    private WebElement element;
    private WebDriver webDriver;

    public AbstractObject(WebElement element) {
        this.element = element;
        setProperties();
    }

    public AbstractObject(WebElement element, WebDriver webDriver) {
        this.element = element;
        this.webDriver = webDriver;
        setProperties();
    }

    public WebDriver getWebDriver() {
        return webDriver;
    }

    public WebElement getElement() {
        return element;
    }

    abstract public void setProperties();

    public void click() {
        element.click();
    }

    abstract public Boolean rowIsEqual(Map<String, String> row);

    abstract public String getObjectLocator();
}
