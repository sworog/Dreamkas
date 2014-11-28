package ru.dreamkas.pages.cashFlow.modal;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;

public class CashFlowEditModalPage extends CashFlowCreateModalPage{

    public CashFlowEditModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        putDefaultConfirmationOkButton(
                new PrimaryBtnFacade(this, "Сохранить"));
    }
}
