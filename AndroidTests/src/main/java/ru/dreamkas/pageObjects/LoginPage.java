package ru.dreamkas.pageObjects;

import ru.dreamkas.pageObjects.elements.Button;
import ru.dreamkas.pageObjects.elements.Input;
import ru.dreamkas.pageObjects.elements.TextView;

public class LoginPage extends CommonPageObject {

    public LoginPage(){
        putElementable("Войти", new Button(this, "btnLogin"));
        putElementable("описание", new TextView(this, "description"));
        putElementable("пользователь",new Input(this, "txtUsername"));
        putElementable("пароль",new Input(this, "txtPassword"));
    }
}
