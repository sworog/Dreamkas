package project.lighthouse.autotests.objects.notApi.abstractObjects;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class AbstractSearchObjectNode extends AbstractObject {

    public AbstractSearchObjectNode(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
    }

    @Override
    public Boolean rowIsEqual(Map<String, String> row) {
        return null;
    }

    @Override
    public String getObjectLocator() {
        return null;
    }

    public List<String> getHighLightTexts() {
        List<String> highLightTexts = new ArrayList<>();
        for (WebElement currentElement : element.findElements(By.xpath(".//*[@class='page__highlighted']"))) {
            highLightTexts.add(currentElement.getText());
        }
        return highLightTexts;
    }
}
