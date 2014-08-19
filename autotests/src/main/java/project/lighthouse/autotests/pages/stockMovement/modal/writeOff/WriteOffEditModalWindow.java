package project.lighthouse.autotests.pages.stockMovement.modal.writeOff;

import org.openqa.selenium.WebDriver;

public class WriteOffEditModalWindow extends WriteOffCreateModalWindow {

    public WriteOffEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_writeOffEdit']";
    }
}
