package ru.dreamkas.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;

public class LoginSteps extends GeneralSteps {

    public LoginSteps(Pages pages) {
        super(pages);
        setCurrentPageObject("экран логина");
    }

    @Step
    public void inputLoginCredentials(String email, String password) {
        setValue("пользователь", email);
        setValue("пароль", password);
    }

    @Step
    public void clickOnLoginButton() {
        clickOnElement("Войти");
    }

    @Step
    public void assertDescription(String expectedDescription) {
        assertText("описание", expectedDescription);
    }
}