package project.lighthouse.autotests.pages.stockMovement.modal.writeOff;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

public class WriteOffEditModalWindow extends WriteOffCreateModalWindow {

    public WriteOffEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_writeOffEdit']";
    }

    public void deleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='removeLink']")).click();
    }

    public void confirmDeleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']//*[@class='removeLink invoice__removeLink']")).click();
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }
}
