package project.lighthouse.autotests.pages.pos;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.collection.refund.RefundProduct;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

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
    }

    @Override
    public AbstractObjectCollection getObjectCollection() {
        return new AbstractObjectCollection(getDriver(), By.name("position")) {

            @Override
            public RefundProduct createNode(WebElement element) {
                return new RefundProduct(element);
            }
        };
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Вернуть").click();
    }
}
