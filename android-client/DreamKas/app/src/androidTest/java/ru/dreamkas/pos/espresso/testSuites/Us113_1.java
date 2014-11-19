package ru.dreamkas.pos.espresso.testSuites;

import android.app.Activity;
import android.test.ActivityInstrumentationTestCase2;

import com.google.android.apps.common.testing.testrunner.ActivityLifecycleMonitorRegistry;
import com.google.android.apps.common.testing.testrunner.Stage;
import com.google.android.apps.common.testing.ui.espresso.Espresso;

import org.hamcrest.Matchers;

import java.util.Collection;
import java.util.concurrent.Callable;
import java.util.concurrent.FutureTask;
import java.util.concurrent.RunnableFuture;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.espresso.ScreenshotFailureHandler;
import ru.dreamkas.pos.espresso.steps.CommonSteps;
import ru.dreamkas.pos.espresso.steps.CommonThen;
import ru.dreamkas.pos.espresso.steps.LoginSteps;
import ru.dreamkas.pos.espresso.steps.LoginThen;
import ru.dreamkas.pos.view.activities.LoginActivity_;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.setFailureHandler;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;

public class Us113_1 extends BaseTestSuite<LoginActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_1()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }



    public void testUserWillGetErrorMessagesIfTryToLoginWithEmptyCredentials() throws Exception
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

    public void testUserWillAuthorizeSuccessFully() throws Exception
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
