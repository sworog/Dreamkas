package project.lighthouse.autotests.objects.web.grossSaleByTable;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class GrossSaleByProductsObjectCollection extends AbstractObjectCollection {

    public GrossSaleByProductsObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public GrossSaleByProductsObject createNode(WebElement element) {
        return new GrossSaleByProductsObject(element);
    }
}
