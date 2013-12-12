package project.lighthouse.autotests.objects.web.reports.grossSaleByStores;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class GrossSalesByStoresCollection extends AbstractObjectCollection {

    public GrossSalesByStoresCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public GrossSaleByStoresObject createNode(WebElement element) {
        return new GrossSaleByStoresObject(element);
    }
}
