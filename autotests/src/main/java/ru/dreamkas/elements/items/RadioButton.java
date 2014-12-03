package ru.dreamkas.elements.items;

import org.openqa.selenium.By;
import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.pages.modal.ModalWindowPage;

public class RadioButton extends CommonItem {

    public RadioButton(ModalWindowPage modalWindowPage, String xpath) {
        super(modalWindowPage, xpath);
    }

    @Override
    public void setValue(String value) {
        String xpath = String.format("%s//*[@class='radioGroup__text' and normalize-space(text())='%s']",
                ((ModalWindowPage)getPageObject()).modalWindowXpath(),
                value);
       getPageObject().findVisibleElement(By.xpath(xpath)).click();
    }
}
