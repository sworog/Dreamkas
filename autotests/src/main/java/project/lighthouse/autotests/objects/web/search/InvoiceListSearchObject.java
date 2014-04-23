package project.lighthouse.autotests.objects.web.search;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

/**
 * Object to store invoice search result
 */
public class InvoiceListSearchObject extends AbstractObject implements ObjectLocatable, ObjectClickable, ResultComparable {

    private String number;
    private String highLightedText;

    public InvoiceListSearchObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        number = getElement().findElement(By.name("number")).getText();
        highLightedText = setProperty(By.className("page__highlighted"));
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return number;
    }

    public String getHighLightedText() {
        return highLightedText;
    }


    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults().compare("number", number, row.get("number"));
    }
}
