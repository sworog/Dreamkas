package project.lighthouse.autotests.pages.commercialManager.supplier;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.Input;
import project.lighthouse.autotests.pageElements.supplier.UploadForm;

/**
 * Page object representing supplier create/edit page
 */
@DefaultUrl("/suppliers/create")
public class SupplierPage extends CommonPageObject {


    private UploadForm uploadForm;

    public SupplierPage(WebDriver driver) {
        super(driver);
    }

    public UploadForm getUploadForm() {
        return uploadForm;
    }

    @Override
    public void createElements() {
        items.put("supplierName", new Input(this, "name", "Юридическое лицо"));
    }

    public ButtonFacade getCreateButtonFacade() {
        return new ButtonFacade(this, "Сохранить");
    }

    public LinkFacade getCancelButtonLinkFacade() {
        return new LinkFacade(this, "Отменить");
    }
}
