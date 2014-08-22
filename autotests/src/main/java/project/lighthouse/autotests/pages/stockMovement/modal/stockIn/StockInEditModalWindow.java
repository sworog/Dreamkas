package project.lighthouse.autotests.pages.stockMovement.modal.stockIn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;

public class StockInEditModalWindow extends StockInCreateModalWindow {

    public StockInEditModalWindow(WebDriver driver) {
        super(driver);
    }

    public void deleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='removeLink']")).click();
    }

    public void confirmDeleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']//*[@class='removeLink stockIn__removeLink']")).click();
    }

    public void addProductToStockInButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addStockInProduct')]")).click();
    }
}
