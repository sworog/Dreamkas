package project.lighthouse.autotests.pages.modal;

import net.thucydides.core.annotations.WhenPageOpens;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.DefaultBtnFacade;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Common page object representing modal window
 */
public class ModalWindowPage extends CommonPageObject {

    public ModalWindowPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public String getTitleText() {
        return findVisibleElement(By.name("title")).getText();
    }

    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }

    public void confirmationCancelClick() {
        new DefaultBtnFacade(this, "Отменить").click();
    }

    @WhenPageOpens
    public void whenPageOpens() {
        //Check that modal window is open
        findVisibleElement(By.id("modal-group"));
    }
}
