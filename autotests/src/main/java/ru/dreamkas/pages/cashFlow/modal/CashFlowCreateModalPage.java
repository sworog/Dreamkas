package ru.dreamkas.pages.cashFlow.modal;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.JSInput;
import ru.dreamkas.elements.items.SelectByLabel;
import ru.dreamkas.pages.modal.ModalWindowPage;

public class CashFlowCreateModalPage extends ModalWindowPage{

    public CashFlowCreateModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_cashFlow']";
    }

    @Override
    public void createElements() {
        put("Тип", new SelectByLabel(this, "type"));
        put("Дата", getCustomJsInput());
        put("Сумма", new Input(this, "amount"));
        put("Комментарий", new Input(this, "comment"));
        putDefaultConfirmationOkButton(
                new PrimaryBtnFacade(this, "Добавить"));
    }

    private JSInput getCustomJsInput() {
        return new JSInput(this, "date") {

            @Override
            public void evaluateUpdatingQueryScript() {
            }
        };
    }
}
