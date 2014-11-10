package ru.dreamkas.pages.user;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.exceptions.NotImplementedException;

public class Settings extends BootstrapPageObject {

    public Settings(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        throw new NotImplementedException();
    }

    @Override
    public void createElements() {
        put("Старый пароль", new Input(this, "oldPassword"));
        put("Новый пароль", new Input(this, "newPassword"));
        put("Подтверждение пароля", new Input(this, "confirmPassword"));
        put("кнопка сохранения нового пароля", new PrimaryBtnFacade(this, "Сохранить"));
        put("сообщение о подтвержении смены пароля", new NonType(this, By.xpath("")));
    }
}
