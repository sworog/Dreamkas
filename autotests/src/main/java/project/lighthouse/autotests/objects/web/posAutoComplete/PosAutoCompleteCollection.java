package project.lighthouse.autotests.objects.web.posAutoComplete;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class PosAutoCompleteCollection extends AbstractObjectCollection<PosAutoCompeteResult> {

    public PosAutoCompleteCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@class='productFinder__resultLink list-group-item']"));
    }

    @Override
    public PosAutoCompeteResult createNode(WebElement element) {
        return new PosAutoCompeteResult(element);
    }
}
