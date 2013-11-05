package project.lighthouse.autotests.objects.notApi.log;

import junit.framework.Assert;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObjectCollection;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class SimpleLogObjectCollection extends AbstractObjectCollection {
    public SimpleLogObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new SimpleLogObject(element);
    }

    @Override
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        List<Map<String, String>> notFoundRows = new ArrayList<>();
        for (Map<String, String> row : examplesTable.getRows()) {
            Boolean found = false;
            for (AbstractObject abstractObject : this) {
                SimpleLogObject simpleLogObject = (SimpleLogObject) abstractObject;
                if (simpleLogObject.rowContains(row)) {
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
