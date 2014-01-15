package project.lighthouse.autotests.objects.web.grossSaleByTable;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

import java.util.List;

public class GrossSaleByTableObjectCollection extends AbstractObjectCollection {

    public GrossSaleByTableObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public GrossSaleByTableObject createNode(WebElement element) {
        return null;
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            AbstractObject abstractObject = new GrossSaleByTableObject(element, findBy, webDriver);
            add(abstractObject);
        }
    }
}
