package project.lighthouse.autotests.pages.stockMovement.modal.supplierReturn;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.objects.web.stockMovement.supplierReturn.SupplierReturnProductCollection;
import project.lighthouse.autotests.pages.stockMovement.modal.StockMovementModalPage;

public class SupplierReturnCreateModalWindow extends StockMovementModalPage {

    public SupplierReturnCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        put("supplier", new SelectByVisibleText(this, "//*[@name='supplier']"));
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Вернуть");
    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@class, 'modal_supplierReturn')]";
    }

    @Override
    public SupplierReturnProductCollection getProductCollection() {
        return new SupplierReturnProductCollection(getDriver());
    }

    @Override
    public void addProductButtonClick() {
        addProductButtonClick("addSupplierReturnProduct");
    }

    @Override
    public Integer getProductRowsCount() {
        return getProductRowsCount("table_supplierReturnProducts");
    }
}
