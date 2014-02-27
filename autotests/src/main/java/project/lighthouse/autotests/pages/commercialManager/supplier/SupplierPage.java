package project.lighthouse.autotests.pages.commercialManager.supplier;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.Input;

/**
 * Page object representing supplier create/edit page
 */
@DefaultUrl("/suppliers/create")
public class SupplierPage extends CommonPageObject {

    public SupplierPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("supplierName", new Input(this, "name", "Юридическое лицо"));
    }

    public void createButtonClick() {
        new ButtonFacade(this, "Сохранить").click();
    }

    public void cancelButtonClick() {
        new LinkFacade(this, "Отменить").click();
    }
}
