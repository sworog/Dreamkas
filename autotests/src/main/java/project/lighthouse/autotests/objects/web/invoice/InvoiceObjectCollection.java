package project.lighthouse.autotests.objects.web.invoice;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class InvoiceObjectCollection extends AbstractObjectCollection {

    public InvoiceObjectCollection(WebDriver webDriver) {
        super(webDriver, By.className("invoice__link"));
    }

    @Override
    public InvoiceObject createNode(WebElement element) {
        return new InvoiceObject(element);
    }
}
