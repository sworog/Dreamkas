package project.lighthouse.autotests.objects.notApi.search;

import junit.framework.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractSearchObjectNode;

import java.util.ArrayList;
import java.util.List;

public class WriteOffSearchObjectCollection extends AbstractObjectCollection {

    public WriteOffSearchObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractSearchObjectNode createNode(WebElement element) {
        return new WriteOffSearchObject(element);
    }

    public void containsHighLightText(String expectedHighLightText) {
        Boolean found = false;
        List<String> highLightCollection = new ArrayList<>();
        for (AbstractObject abstractObject : this) {
            WriteOffSearchObject writeOffSearchObject = (WriteOffSearchObject) abstractObject;
            highLightCollection.addAll(writeOffSearchObject.getHighLightTexts());
        }
        for (String highLightText : highLightCollection) {
            if (highLightText.equals(expectedHighLightText)) {
                found = true;
                break;
            }
        }
        if (!found) {
            String errorMessage = String.format("No highlightedText '%s'. Actual: '%s'", expectedHighLightText, highLightCollection);
            Assert.fail(errorMessage);
        }
    }
}
