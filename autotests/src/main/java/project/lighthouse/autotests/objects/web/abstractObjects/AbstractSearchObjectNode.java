package project.lighthouse.autotests.objects.web.abstractObjects;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

import java.util.ArrayList;
import java.util.List;

abstract public class AbstractSearchObjectNode extends AbstractObject {

    public AbstractSearchObjectNode(WebElement element) {
        super(element);
    }

    public AbstractSearchObjectNode(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    public List<String> getHighLightTexts() {
        List<String> highLightTexts = new ArrayList<>();
        for (WebElement currentElement : getElement().findElements(By.xpath(".//*[@class='page__highlighted']"))) {
            highLightTexts.add(currentElement.getText());
        }
        return highLightTexts;
    }
}
