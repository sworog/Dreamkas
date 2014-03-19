package project.lighthouse.autotests.pages.departmentManager.order.pageElements;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

/**
 * Page element representing product edition form
 */
public class ProductEditionForm extends ProductAdditionForm {

    public ProductEditionForm(WebDriver driver) {
        super(driver);
    }

    public void editButtonClick() {
        new ButtonFacade(this, "Редактировать").click();
        new PreLoader(getDriver()).await();
    }
}
