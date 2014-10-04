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
        put("сумма к оплате", new NonType(this, "//*[@name='totalPrice']"));
        put("сдача", new NonType(this, "//*[@name='change']"));
        put("внесенная сумма наличными", new Input(this, "//*[@name='payment.amountTendered']"));
        put("тип оплаты", new SelectByLabel(this, "//*[@name='payment.type']"));
        put("заголовок успешной продажи", new NonType(this, "//*[@class='modal_receipt__successTitle']"));
        put("сумма сдачи, необходимую выдать", new NonType(this, "//*[@class='modal_receipt__changeSum']"));
        put("тип оплаты по умолчанию", new NonType(this, "//label[input[@name='payment.type' and @checked='']]"));
        put("continueButton", new PrimaryBtnFacade(this, "Продолжить работу"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Рассчитать").click();
    }
}
