package project.lighthouse.autotests.collection.product.autocomplete;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;

/**
 * The object collection to store group autocomplete results
 */
public class GroupAutoCompleteResultCollection<E extends GroupAutoCompleteResult> extends AbstractObjectCollection<E> {

    public GroupAutoCompleteResultCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@class='select2-result-label']"));
    }

    @Override
    @SuppressWarnings("unchecked")
    public E createNode(WebElement element) {
        return (E) new GroupAutoCompleteResult(element);
    }
}
