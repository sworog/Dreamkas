package project.lighthouse.autotests.objects.notApi.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObjectCollection;

import java.util.List;

public class LogObjectCollection extends AbstractObjectCollection {

    public LogObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            LogObject logObject = new LogObject(element, webDriver);
            add(logObject);
        }
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return null;
    }
}
