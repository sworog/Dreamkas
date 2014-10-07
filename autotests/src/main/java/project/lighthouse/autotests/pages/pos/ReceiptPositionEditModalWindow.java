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
        put("sellingPrice", new Input(this, "//*[@name='price']"));
        put("quantity", new Input(this, "//*[@name='quantity']"));
        put("itemPrice", new NonType(this, "//*[@name='itemPrice']"));
        put("name", new NonType(this, "//*[@name='name']"));
        put("barcode", new NonType(this, "//*[@name='barcode']"));
        put("plusButton", new NonType(this, "//*[contains(@class, 'inputNumber__countUp')]"));
        put("minusButton", new NonType(this, "//*[contains(@class, 'inputNumber__countDown')]"));
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_receiptProduct']";
    }

    @Override
    public void deleteButtonClick() {
        clickInTheModalWindowByXpath("//*[@class='removeLink']");
    }

    @Override
    public void confirmDeleteButtonClick() {
        clickInTheModalWindowByXpath("//*[@class='confirmLink__confirmation']//*[@class='removeLink form_receiptProduct__removeLink']");
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal__title']")).getText();
    }
}
