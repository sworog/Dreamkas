package project.lighthouse.autotests.elements.items;

import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class DateInput extends Input {

    By inputLabelBy = By.xpath("./../../label[@class='control-label']");

    public DateInput(ModalWindowPage modalWindowPage, String xpath) {
        super(modalWindowPage, xpath);
    }

    @Override
    public void setValue(String value) {
        getVisibleWebElementFacade().type(value);
        clickToCloseCalendar();
    }

    // Workaround for datepicker closing
    protected void clickToCloseCalendar() {
        getVisibleWebElementFacade().findElement(inputLabelBy).click();
    }
}
