package ru.dreamkas.pages.cashFlow.modal;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.apihelper.DateTimeHelper;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.JSInput;
import ru.dreamkas.elements.items.RadioButton;
import ru.dreamkas.handler.field.FieldChecker;
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
        put("Тип", new RadioButton(this, "//*[@name='direction' and @checked]/.."));
        put("Дата", getCustomJsInput());
        put("Сумма", new Input(this, "//*[@name='amount']"));
        put("Комментарий", new Input(this, "//*[@name='comment']"));
        putDefaultConfirmationOkButton(
                new PrimaryBtnFacade(this, "Добавить"));
    }

    private JSInput getCustomJsInput() {
        return new JSInput(this, "date") {

            @Override
            public void evaluateUpdatingQueryScript() {
            }

            @Override
            public String getText() {
                return getVisibleWebElementFacade().getText();
            }

            @Override
            public FieldChecker getFieldChecker() {
                return new FieldChecker(this) {
                    @Override
                    public void assertValueEqual(String expectedValue) {
                        String convertedDate = DateTimeHelper.getDate(expectedValue);
                        super.assertValueEqual(convertedDate);
                    }
                };
            }
        };
    }
}
