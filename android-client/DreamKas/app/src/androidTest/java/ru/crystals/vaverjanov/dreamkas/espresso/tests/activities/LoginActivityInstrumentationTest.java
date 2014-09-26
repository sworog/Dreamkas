package ru.crystals.vaverjanov.dreamkas.espresso.tests.activities;

import android.app.Activity;
import android.test.ActivityInstrumentationTestCase2;
import com.google.android.apps.common.testing.testrunner.ActivityLifecycleMonitorRegistry;
import com.google.android.apps.common.testing.testrunner.Stage;
import com.google.android.apps.common.testing.ui.espresso.Espresso;
import com.google.android.apps.common.testing.ui.espresso.action.ViewActions;
import com.google.common.base.Preconditions;
import com.google.common.collect.Iterables;
import com.squareup.spoon.Spoon;

import junit.framework.Assert;

import org.hamcrest.Matchers;

import java.util.Collection;
import java.util.concurrent.Callable;
import java.util.concurrent.FutureTask;
import java.util.concurrent.RunnableFuture;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.RequestIdlingResource;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.ScreenshotFailureHandler;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity_;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.setFailureHandler;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.doesNotExist;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.RootMatchers.withDecorView;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.assertThat;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDescendantOfA;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.*;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static org.hamcrest.CoreMatchers.not;
import static org.hamcrest.core.AllOf.allOf;
import static org.hamcrest.core.Is.is;
import static ru.crystals.vaverjanov.dreamkas.espresso.helpers.EspressoExtends.hasErrorText;
import static ru.crystals.vaverjanov.dreamkas.espresso.helpers.EspressoExtends.waitKeyboard;
import static ru.crystals.vaverjanov.dreamkas.espresso.helpers.EspressoExtends.withResourceName;


public class LoginActivityInstrumentationTest extends ActivityInstrumentationTestCase2<LoginActivity_>
{
    private LoginActivity mStartActivity;

    @SuppressWarnings("deprecation")
    public LoginActivityInstrumentationTest()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }

    @Override
    protected void setUp() throws Exception
    {
        super.setUp();

        mStartActivity = getActivity();

        //register idling resource for auth request listener
        Espresso.registerIdlingResources(new RequestIdlingResource(mStartActivity.authRequestListener));
        setFailureHandler(new ScreenshotFailureHandler(getInstrumentation().getTargetContext(), mStartActivity));
    }

    @Override
    public void tearDown() throws Exception {
        super.tearDown();
    }

    public void testUserWillGetErrorMessagesIfTryToLoginWithEmptyCredentials() throws Exception
    {
        //enter empty credentials
        enterCredentialsAndClick("", "");

        //check error icon visibility inside edittext
        onView(withId(R.id.txtUsername)).check(matches(hasErrorText(getActivity().getString(R.string.error_empty_field))));

        //check error icon visibility inside edittext
        onView(withId(R.id.txtPassword)).check(matches(hasErrorText(getActivity().getString(R.string.error_empty_field))));
    }

    public void testUserWillGetErrorToastMessageIfTryToLoginWithWrongCredentials() throws Exception
    {
        //enter wrong credentials
        enterCredentialsAndClick("wrong_name", "wrong_password");

        //expect toast with error message
        onView(withText(R.string.error_bad_credentials)).inRoot(withDecorView(not(is(getActivity().getWindow().getDecorView())))).check(matches(isDisplayed()));

    }

    public void testUserWillAuthorizeSuccessFully() throws Exception
    {
        //enter right credentials
        enterCredentialsAndClick("owner@lighthouse.pro", "lighthouse");

        //check if loading dialog is displayed
        //onView(withText(R.string.auth_dialog_title)).check(matches(isDisplayed()));

        //check that loading dialog is dismissed
        onView(withText(R.string.auth_dialog_title)).check(doesNotExist());

        //here we got new opened activity after login and here we should see action bar with title
        onView(allOf(isDescendantOfA(withResourceName("android:id/action_bar_container")), withText(R.string.dream_kas_title)));

        //check that btnLogin does not exist in current context
        onView(withId(R.id.btnLogin)).check(doesNotExist());

        assertThat("Login activity doesn't destroy after login", mStartActivity.isFinishing(), Matchers.is(true));
    }

    public void testLoginActivityIsSingleInstanceAfterLogOut() throws Exception
    {
        enterCredentialsAndClick("owner@lighthouse.pro", "lighthouse");

        //now on back button click we expected exit from app, because login activity disappeared
        pressBack();

        RunnableFuture activityInStageGetterRunnable = new FutureTask(new Callable<Integer>()
        {
            @Override
            public Integer call() throws Exception
            {
                Collection<Activity> activities = ActivityLifecycleMonitorRegistry.getInstance().getActivitiesInStage(Stage.RESUMED);
                return activities.size();
            }
        });

        getInstrumentation().runOnMainSync(new Thread(activityInStageGetterRunnable));
        assertThat("StoreFragment does't exists in current activity",(Integer)activityInStageGetterRunnable.get(), Matchers.equalTo(1));
    }

    private void enterCredentialsAndClick(String userName, String password)
    {
        onView(withId(R.id.txtUsername)).perform(clearText()).perform(ViewActions.typeText(userName), closeSoftKeyboard());

        //hack, espresso bug here. without waiting cause exception
        waitKeyboard(200);

        onView(withId(R.id.txtPassword)).perform(clearText()).perform(typeText(password), closeSoftKeyboard());

        //hack, espresso bug here. without waiting cause exception
        waitKeyboard(200);

        //click on login button
        onView(withId(R.id.btnLogin)).perform(click());
    }
}