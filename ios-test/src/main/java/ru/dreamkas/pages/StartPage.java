package ru.dreamkas.pages;

import ru.dreamkas.elements.Button;

public class StartPage extends CommonPageObject {

    @Override
    public void createElements() {
        putElement("Войти", new Button(this, "AI_AuthPage_LoginButton"));
    }
}
