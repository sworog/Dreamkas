package project.lighthouse.autotests.objects.web.receipt;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class ReceiptCollection<E extends ReceiptObject> extends AbstractObjectCollection<E> {

    public ReceiptCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@name='productList']/tbody/*[@name='product']"));
    }

    @Override
    public E createNode(WebElement element) {
        return (E) (new ReceiptObject(element));
    }
}
