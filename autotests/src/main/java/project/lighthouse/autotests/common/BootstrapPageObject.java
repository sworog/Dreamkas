package project.lighthouse.autotests.common;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;

public abstract class BootstrapPageObject extends CommonPageObject {

    public BootstrapPageObject(WebDriver driver) {
        super(driver);
    }

    public abstract void addObjectButtonClick();

    public String getTitle() {
        return findVisibleElement(By.className("page-title")).getText();
    }
}
