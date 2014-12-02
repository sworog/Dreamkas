package ru.dreamkas.pages.cashFlow.modal;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.NonType;

public class CashFlowEditModalPage extends CashFlowCreateModalPage{

    public CashFlowEditModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        putDefaultConfirmationOkButton(
                new PrimaryBtnFacade(this, "Сохранить"));
        put("заголовок успешного удаления денежной операции", new NonType(this, "//*[@name='successRemoveTitle']"));
    }
}
