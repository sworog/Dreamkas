package project.lighthouse.autotests.pages.logPages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.notApi.log.LogObject;
import project.lighthouse.autotests.objects.notApi.log.LogObjectCollection;

public class LogPage extends CommonPageObject {

    public LogPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public LogObjectCollection getLogObjectCollection() {
        return new LogObjectCollection(getDriver(), By.xpath("//*[@class='log__item']"));
    }

    public LogObject getLastLogObject() {
        return getLogObjectCollection().get(0);
    }
}
