package project.lighthouse.autotests.pages.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

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
        //Сохранить
    }

    public void confirmationCancelClick() {
        //Отменить
    }
}
