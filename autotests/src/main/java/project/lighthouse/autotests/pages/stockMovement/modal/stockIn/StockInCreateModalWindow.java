package project.lighthouse.autotests.pages.stockMovement.modal.stockIn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.objects.web.stockIn.StockInProductCollection;
import project.lighthouse.autotests.pages.stockMovement.modal.writeOff.WriteOffCreateModalWindow;

public class StockInCreateModalWindow extends WriteOffCreateModalWindow {

    public StockInCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@class, 'modal_stockIn')]";
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Оприходовать").click();
    }

    public void addProductToStockInButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addStockInProduct')]")).click();
    }

    public StockInProductCollection getStockInProductCollection() {
        return new StockInProductCollection(getDriver());
    }
}
