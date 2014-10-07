package project.lighthouse.autotests.pages.pos;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.pageObjects.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;

@DefaultUrl("/pos")
public class PosLaunchPage extends BootstrapPageObject {

    public PosLaunchPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Далее").click();
    }

    @Override
    public void createElements() {
        put("store", new SelectByVisibleText(this, "store"));
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.className("page__title")).getText();
    }
}
