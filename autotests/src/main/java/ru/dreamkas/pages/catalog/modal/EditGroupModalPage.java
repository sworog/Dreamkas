package ru.dreamkas.pages.catalog.modal;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.NonType;

/**
 * Edit group modal page object
 */
public class EditGroupModalPage extends CreateGroupModalPage {

    public EditGroupModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        put("заголовок успешного удаления", new NonType(this, "//*[@name='successRemoveTitle']"));
        put("название удаленной группы", new NonType(this, "//*[@name='removedGroupName']"));
        putDefaultConfirmationOkButton(
                new PrimaryBtnFacade(this, "Сохранить"));
    }
}
