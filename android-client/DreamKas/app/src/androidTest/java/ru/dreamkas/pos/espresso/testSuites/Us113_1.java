package ru.dreamkas.pos.espresso.testSuites;

import ru.dreamkas.pos.espresso.steps.CommonThen;
import ru.dreamkas.pos.espresso.steps.LoginSteps;
import ru.dreamkas.pos.espresso.steps.LoginThen;
import ru.dreamkas.pos.view.activities.LoginActivity_;
import ru.dreamkas.pos.view.activities.WelcomeActivity_;

public class Us113_1 extends BaseTestSuite<WelcomeActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_1()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.dreamkas.pos.view.activities", WelcomeActivity_.class);
    }

    public void testUserWillGetErrorMessagesIfTryToLoginWithEmptyCredentials() throws Throwable
    {
        LoginSteps.enterCredentialsAndClick("", "");
        LoginThen.checkEmptyCredentials();
    }

    public void testUserWillGetErrorToastMessageIfTryToLoginWithWrongCredentials() throws Throwable
    {
        //enter wrong credentials
        LoginSteps.enterCredentialsAndClick("wrong_name", "wrong_password");

        CommonThen.checkToast("Неверный логин или пароль", getCurrentActivity());
    }

    public void testUserWillAuthorizeSuccessFully() throws Throwable
    {
        //enter right credentials
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        LoginThen.checkSuccessAuth();
    }

    /*public void testLoginActivityIsSingleInstanceAfterLogOut() throws Exception
    {
        //LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        //now on back button click we expected exit from app, because login activity disappeared
        //CommonSteps.pressBackButton();
        //LoginThen.checkResumedActivitiesCount(getInstrumentation());
    }*/
}
