package project.lighthouse.autotests.pages.pos;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class ReceiptModalPage extends ModalWindowPage{

    public ReceiptModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_Receipt']";
    }

    @Override
    public void createElements() {
        put("totalSum", new NonType(this, "//*[@name='modal_receipt']"));
        put("change", new NonType(this, "//*[@name='change']"));
        put("payment.amountTendered", new Input(this, "//*[@name='payment.amountTendered']"));
        put("payment.type", new Input(this, "//*[@name='payment.type']"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Рассчитать").click();
    }
}
