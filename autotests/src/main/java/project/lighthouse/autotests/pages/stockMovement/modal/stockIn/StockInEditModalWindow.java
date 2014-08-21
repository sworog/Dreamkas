package project.lighthouse.autotests.pages.stockMovement.modal.stockIn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.stockMovement.modal.writeOff.WriteOffEditModalWindow;

public class StockInEditModalWindow extends WriteOffEditModalWindow {

    public StockInEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_stockInEdit']";
    }

    @Override
    public void confirmDeleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']//*[@class='removeLink stockIn__removeLink']")).click();
    }

    public void addProductToStockInButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addStockInProduct')]")).click();
    }
}
