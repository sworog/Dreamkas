package ru.dreamkas.pages.pos;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.collection.refund.RefundProduct;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.pages.modal.ModalWindowPage;

public class RefundModalWindowPage extends ModalWindowPage {

    public RefundModalWindowPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_refund']";
    }

    @Override
    public void createElements() {
        put("сумма возврата на кнопке вернуть", new NonType(this, "//*[contains(@class, 'modal_refund__button')]"));
        put("заголовок успешного возврата", new NonType(this, "//*[@class='modal_receipt__successTitle']"));
        put("сумма сдачи, необходимую выдать", new NonType(this, "//*[@class='modal_receipt__changeSum']"));
        putDefaultCollection(new AbstractObjectCollection(getDriver(), By.name("position")) {

            @Override
            public RefundProduct createNode(WebElement element) {
                return new RefundProduct(element);
            }
        });
        put("continueButton", new PrimaryBtnFacade(this, "Продолжить работу"));

        putDefaultConfirmationOkButton(new NonType(this, By.xpath("//*[contains(@class, 'btn btn-primary') and contains(text(), 'Вернуть')]")));
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal__title']")).getText();
    }
}
