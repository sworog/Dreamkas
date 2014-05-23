package project.lighthouse.autotests.pages.commercialManager.product;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.objects.web.product.barcodes.BarcodeObjectCollection;

public class ExtraBarcodesPage extends CommonPageObject {

    public ExtraBarcodesPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("barcode", new Input(this, "barcode"));
        put("quantity", new Input(this, "quantity"));
        put("price", new Input(this, "price"));
    }

    public ButtonFacade getAddBarcodeButton() {
        return new ButtonFacade(this, "Добавить");
    }

    public ButtonFacade getSaveBarcodeButton() {
        return new ButtonFacade(this, "Сохранить");
    }

    public LinkFacade getCancelLink() {
        return new LinkFacade(this, "Отменить");
    }

    public BarcodeObjectCollection getBarcodeObjectCollection() {
        return new BarcodeObjectCollection(getDriver(), By.name("barcodeRow"));
    }
}
