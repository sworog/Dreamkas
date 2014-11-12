package ru.dreamkas.pages.dialogs;

import ru.dreamkas.elements.Button;
import ru.dreamkas.elements.Input;
import ru.dreamkas.pages.CommonPageObject;

public class LoginDialogPage extends CommonPageObject {

    @Override
    public void createElements() {
        putElement("Логин", new Input(this, "AI_LogInPage_LoginField"));
        putElement("Пароль", new Input(this, "AI_LogInPage_PwdField"));
        putElement("Войти", new Button(this, "AI_LogInPage_LogInButton"));
    }
}
