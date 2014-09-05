package project.lighthouse.autotests.pages.pos;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class ReceiptPositionEditModalWindow extends ModalWindowPage {

    public ReceiptPositionEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("sellingPrice", new Input(this, "//*[@name='sellingPrice']"));
        put("count", new Input(this, "//*[@name='count']"));
        put("itemPrice", new NonType(this, "//*[@name='itemPrice']"));
        put("name", new NonType(this, "//*[@name='name']"));
        put("barcode", new NonType(this, "//*[@name='barcode']"));
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_receiptProduct']";
    }

    public void clickOnPlusButton() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'form_receiptProduct__countPlusLink')]")).click();
    }

    public void clickOnMinusButton() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'form_receiptProduct__countMinusLink')]")).click();
    }

    @Override
    public void deleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='removeLink']")).click();
    }

    @Override
    public void confirmDeleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']//*[@class='removeLink form_receiptProduct__removeLink']")).click();
    }
}
