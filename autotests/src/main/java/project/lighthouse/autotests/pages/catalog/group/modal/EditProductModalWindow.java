package project.lighthouse.autotests.pages.catalog.group.modal;

import org.openqa.selenium.WebDriver;
import sun.reflect.generics.reflectiveObjects.NotImplementedException;

/**
 * Edit product modal window
 */
public class EditProductModalWindow extends CreateNewProductModalWindow {

    public EditProductModalWindow(WebDriver driver) {
        super(driver);
    }

    public void deleteButtonClick() {
        throw new NotImplementedException();
    }

    public void confirmDeleteButtonClick() {
        throw new NotImplementedException();
    }

    @Override
    public String getTitleText() {
        throw new NotImplementedException();
    }
}
