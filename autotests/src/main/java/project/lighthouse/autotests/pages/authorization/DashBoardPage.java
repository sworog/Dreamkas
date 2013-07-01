package project.lighthouse.autotests.pages.authorization;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

public class DashBoardPage extends CommonPageObject {

    public DashBoardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    private String getButtonXpath(String name) {
        return String.format("//*[@href='/%s']", name);
    }

    public void buttonClick(String name) {
        String buttonXpath = getButtonXpath(name);
        findVisibleElement(By.xpath(buttonXpath)).click();
    }

    public void shouldNotBeVisible(String name) {
        String buttonXpath = getButtonXpath(name);
        waiter.waitUntilIsNotVisible(By.xpath(buttonXpath));
    }

    public void shouldBeVisible(String name) {
        String buttonXpath = getButtonXpath(name);
        waiter.getVisibleWebElement(By.xpath(buttonXpath));
    }
}
