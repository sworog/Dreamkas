package ru.dreamkas.pos.espresso.steps;

import ru.dreamkas.pos.R;

public class LoginSteps {
    public static void enterCredentialsAndClick(String userName, String password) throws Throwable {
        CommonSteps.clickOnViewWithId(R.id.btnLogin);

        CommonSteps.typeOnViewWithId(R.id.txtUsername, userName);
        CommonSteps.typeOnViewWithId(R.id.txtPassword, password);

        CommonSteps.clickOnViewWithId(R.id.btnLogin);
    }
}
