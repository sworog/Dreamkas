package project.lighthouse.autotests.objects.web.grossSaleByTable;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

import java.util.List;

public class GrossSaleByProductsObjectCollection extends AbstractObjectCollection {

    public GrossSaleByProductsObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            AbstractObject abstractObject = new GrossSaleByProductsObject(element, webDriver);
            add(abstractObject);
        }
    }

    @Override
    public GrossSaleByProductsObject createNode(WebElement element) {
        return null;
    }
}
