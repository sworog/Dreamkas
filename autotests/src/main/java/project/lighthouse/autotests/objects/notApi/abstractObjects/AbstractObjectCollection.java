package project.lighthouse.autotests.objects.notApi.abstractObjects;

import junit.framework.Assert;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

abstract public class AbstractObjectCollection extends ArrayList<AbstractObject> {

    public AbstractObjectCollection(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            AbstractObjectNode abstractObjectNode = createNode(element);
            add(abstractObjectNode);
        }
    }

    abstract public AbstractObjectNode createNode(WebElement element);

    public void compareWithExampleTable(ExamplesTable examplesTable) {

        List<Map<String, String>> notFoundRows = new ArrayList<>();
        for (Map<String, String> row : examplesTable.getRows()) {
            Boolean found = false;
            for (AbstractObject abstractObject : this) {
                if (abstractObject.rowIsEqual(row)) {
                    found = true;
                    break;
                }
            }
            if (!found) {
                notFoundRows.add(row);
            }
        }
        if (notFoundRows.size() > 0) {
            String errorMessage = String.format("These rows are not found: '%s'.", notFoundRows.toString());
            Assert.fail(errorMessage);
        }
    }
}
