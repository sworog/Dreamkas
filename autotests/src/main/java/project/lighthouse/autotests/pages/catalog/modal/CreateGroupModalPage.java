package project.lighthouse.autotests.pages.catalog.modal;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

/**
 * Create group modal page element
 */
public class CreateGroupModalPage extends ModalWindowPage {

    public CreateGroupModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new Input(this, "name"));
    }
}
