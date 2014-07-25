package project.lighthouse.autotests.objects.web.product.autocomplete;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

/**
 * The object collection to store group autocomplete results
 */
public class GroupAutoCompleteResultCollection extends AbstractObjectCollection {

    public GroupAutoCompleteResultCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@class='select2-result-label']"));
    }

    @Override
    public GroupAutoCompleteResult createNode(WebElement element) {
        return new GroupAutoCompleteResult(element);
    }
}
