package project.lighthouse.autotests.objects.web.search.deprecated;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractSearchObjectCollection;

import java.util.List;

@Deprecated
public class InvoiceSearchObjectCollection extends AbstractSearchObjectCollection {

    public InvoiceSearchObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            AbstractObject abstractObject = new InvoiceSearchObject(webDriver, element);
            add(abstractObject);
        }
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return null;
    }
}
