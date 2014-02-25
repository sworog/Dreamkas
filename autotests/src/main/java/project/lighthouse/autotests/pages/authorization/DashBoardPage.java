package project.lighthouse.autotests.pages.authorization;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

import static junit.framework.Assert.fail;

public class DashBoardPage extends CommonPageObject {

    public DashBoardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    private String getButtonXpath(String name) {
        switch (name) {
            case "users":
                return "//*[contains(@href, '/users') and not(@class='navigationBar__userName')]";
            default:
                return String.format("//*[contains(@href, '/%s')]", name);
        }
    }

    public void buttonClick(String name) {
        String buttonXpath = getButtonXpath(name);
        findVisibleElement(By.xpath(buttonXpath)).click();
    }

    public void shouldNotBeVisible(String name) {
        try {
            String buttonXpath = getButtonXpath(name);
            getWaiter().waitUntilIsNotVisible(By.xpath(buttonXpath));
        } catch (Exception e) {
            fail(
                    String.format("The dashboard '%s' link is present on the page", name)
            );
        }
    }

    public void shouldBeVisible(String name) {
        try {
            String buttonXpath = getButtonXpath(name);
            findVisibleElement(By.xpath(buttonXpath));
        } catch (Exception e) {
            fail(
                    String.format("The dashboard '%s' link is not present on the page", name)
            );
        }
    }

    public void openUserCard() {
        findVisibleElement(By.className("navigationBar__userName")).click();
    }
}
