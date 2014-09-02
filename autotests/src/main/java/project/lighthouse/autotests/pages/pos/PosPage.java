package project.lighthouse.autotests.pages.pos;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.BootstrapPageObject;

public class PosPage extends BootstrapPageObject {

    public PosPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        throw new AssertionError("Not implemented!");
    }

    @Override
    public void createElements() {
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.className("page__title")).getText();
    }
}
