package project.lighthouse.autotests.pages.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

/**
 * Common page element representing modal window
 */
public class ModalWindowPage extends CommonPageObject {

    public ModalWindowPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public String getHeadingText() {
        return findVisibleElement(By.name("heading")).getText();
    }

    public void confirmationOkClick() {
        //Сохранить
    }

    public void confirmationCancelClick() {
        //Отменить
    }
}
