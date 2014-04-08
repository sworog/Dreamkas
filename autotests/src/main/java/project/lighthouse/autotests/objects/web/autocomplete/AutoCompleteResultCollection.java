package project.lighthouse.autotests.objects.web.autocomplete;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

import java.util.List;

/**
 * Collection to store autocomplete results and interact with them
 */
public class AutoCompleteResultCollection extends AbstractObjectCollection {

    public AutoCompleteResultCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            AutoCompleteResult autoCompleteResult = new AutoCompleteResult(element, webDriver);
            add(autoCompleteResult);
        }
    }

    @Override
    public AutoCompleteResult createNode(WebElement element) {
        return null;
    }
}
