package project.lighthouse.autotests.pages.stockMovement.modal.stockIn;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.objects.web.stockIn.StockInProductCollection;
import project.lighthouse.autotests.pages.stockMovement.modal.StockMovementModalPage;

public class StockInCreateModalWindow extends StockMovementModalPage {

    public StockInCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@class, 'modal_stockIn')]";
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Оприходовать");
    }

    public void addProductButtonClick() {
        addProductButtonClick("addStockInProduct");
    }

    public String getTotalSum() {
        return getTotalSum("stockIn__totalSum");
    }

    @Override
    public StockInProductCollection getProductCollection() {
        return new StockInProductCollection(getDriver());
    }

    @Override
    public Integer getProductRowsCount() {
        return getProductRowsCount("table_stockInProducts");
    }
}
