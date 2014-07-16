package project.lighthouse.autotests.pages.catalog.modal;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

/**
 * Delete group modal page object
 */
public class DeleteGroupModalPage extends ModalWindowPage {

    public DeleteGroupModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void confirmationOkClick() {
        //Подтвердить удаление
    }

    @Override
    public void confirmationCancelClick() {
        //Отменить удаление
    }
}
