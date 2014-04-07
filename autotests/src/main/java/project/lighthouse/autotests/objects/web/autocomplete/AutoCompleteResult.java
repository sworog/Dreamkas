package project.lighthouse.autotests.objects.web.autocomplete;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.Highlightable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

/**
 * Object representing single autocomplete result value
 */
public class AutoCompleteResult extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable, Highlightable {

    private String result;
    private String highlightedText;

    public AutoCompleteResult(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        result = getElement().getText();
        highlightedText = setProperty(By.xpath(".//strong"));
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return result;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("result", result, row.get("result"));
    }

    public String getHighlightedText() {
        return highlightedText;
    }

    @Override
    public Boolean isHighlighted() {
        return getElement().getAttribute("class").contains("item_focused");
    }
}
