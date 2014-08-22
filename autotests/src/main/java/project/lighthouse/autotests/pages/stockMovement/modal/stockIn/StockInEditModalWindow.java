package project.lighthouse.autotests.pages.stockMovement.modal.stockIn;

import org.openqa.selenium.WebDriver;

public class StockInEditModalWindow extends StockInCreateModalWindow {

    public StockInEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void confirmDeleteButtonClick() {
        confirmDeleteButtonClick("stockIn__removeLink");
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Сохранить");
    }
}
