package project.lighthouse.autotests.elements.items;

import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class DateInput extends Input {

    public DateInput(ModalWindowPage modalWindowPage, String xpath) {
        super(modalWindowPage, xpath);
    }

    @Override
    public void setValue(String value) {
        getVisibleWebElementFacade().type(value);
        // Workaround for datepicker closing
        getVisibleWebElementFacade().findElement(By.xpath("./../*[@class='input-group-addon']")).click();
    }
}
