package project.lighthouse.autotests.pages.logPages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.log.SimpleLogObjectCollection;

public class LogPage extends CommonPageObject {

    public LogPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public SimpleLogObjectCollection getSimpleLogObjectCollection() {
        return new SimpleLogObjectCollection(getDriver(), By.xpath("//*[@class='log__item']"));
    }
}
