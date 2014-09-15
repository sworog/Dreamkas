package project.lighthouse.autotests.pages.pos;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.elements.items.SelectByLabel;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class ReceiptModalPage extends ModalWindowPage{

    public ReceiptModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_receipt']";
    }

    @Override
    public void createElements() {
        put("totalPrice", new NonType(this, "//*[@name='totalPrice']"));
        put("change", new NonType(this, "//*[@name='change']"));
        put("payment.amountTendered", new Input(this, "//*[@name='payment.amountTendered']"));
        put("payment.type", new SelectByLabel(this, "//*[@name='payment.type']"));
        put("receiptSuccessTitle", new NonType(this, "//*[@class='modal_receipt__successTitle']"));
        put("receiptChangeSum", new NonType(this, "//*[@class='modal_receipt__changeSum']"));
        put("defaultPaymentType", new NonType(this, "//label[input[@name='payment.type' and @checked='']]"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Рассчитать").click();
    }

    public void clickOnContinueButton() {
        new PrimaryBtnFacade(this, "Продолжить работу").click();
    }
}
