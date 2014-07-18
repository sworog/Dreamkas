package project.lighthouse.autotests.pages.catalog.modal;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.items.InputOnlyVisible;

/**
 * Edit group modal page object
 */
public class EditGroupModalPage extends CreateGroupModalPage {

    public EditGroupModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new InputOnlyVisible(this, "name"));
    }

    public void deleteButtonClick() {
        findVisibleElement(By.className("form__groupRemoveLink")).click();
    }
}
