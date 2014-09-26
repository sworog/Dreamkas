package project.lighthouse.autotests.pages.stockMovement.modal.supplierReturn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.collection.stockMovement.supplierReturn.SupplierReturnProductCollection;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.pages.stockMovement.modal.PayableStockMovementModalPage;
import project.lighthouse.autotests.pages.stockMovement.modal.StockMovementModalPage;

public class SupplierReturnCreateModalWindow extends StockMovementModalPage implements PayableStockMovementModalPage {

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

    public void clickPaidCheckBox() {
        String xpath = String.format(
                "%s//*[@name='paid']/../*[@class='checkbox__text' and normalize-space(text())='%s']",
                modalWindowXpath(),
                "Погашен"
        );
        findVisibleElement(By.xpath(xpath)).click();
    }
}
