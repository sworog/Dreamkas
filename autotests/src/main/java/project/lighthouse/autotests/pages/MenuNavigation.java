package project.lighthouse.autotests.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

public class MenuNavigation extends CommonPageObject {

    public MenuNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void reportMenuItemClick() {
        findVisibleElement(By.xpath("//*[@href='/reports']")).click();
    }
}
