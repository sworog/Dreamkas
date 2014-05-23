package project.lighthouse.autotests.objects.web.product.barcodes;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

import java.util.List;

/**
 * Collection to store Barcode objects
 */
public class BarcodeObjectCollection extends AbstractObjectCollection {

    public BarcodeObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            BarcodeObject barcodeObject = new BarcodeObject(element, webDriver);
            add(barcodeObject);
        }
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return null;
    }
}
