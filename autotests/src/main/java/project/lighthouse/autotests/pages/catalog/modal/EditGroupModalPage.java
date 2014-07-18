package project.lighthouse.autotests.pages.catalog.modal;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

/**
 * Edit group modal page object
 */
public class EditGroupModalPage extends ModalWindowPage {

    public EditGroupModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new Input(this, By.xpath("//*[@id='form_groupEdit']//*[@name='name']")));
    }

    public void deleteButtonClick() {
        findVisibleElement(By.className("form__groupRemoveLink")).click();
    }
}
