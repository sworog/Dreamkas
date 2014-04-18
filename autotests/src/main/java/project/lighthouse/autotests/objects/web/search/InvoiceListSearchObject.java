package project.lighthouse.autotests.objects.web.search;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;

/**
 * Object to store invoice search result
 */
public class InvoiceListSearchObject extends AbstractObject implements ObjectLocatable, ObjectClickable {

    private String number;

    public InvoiceListSearchObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        number = getElement().findElement(By.name("number")).getText();
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return number;
    }
}
