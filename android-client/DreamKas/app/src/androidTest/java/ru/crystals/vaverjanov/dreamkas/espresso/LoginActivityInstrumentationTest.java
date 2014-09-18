package ru.crystals.vaverjanov.dreamkas.espresso;

import android.app.Activity;
import android.test.ActivityInstrumentationTestCase2;
import com.google.android.apps.common.testing.testrunner.ActivityLifecycleMonitorRegistry;
import com.google.android.apps.common.testing.testrunner.Stage;
import com.google.android.apps.common.testing.ui.espresso.Espresso;
import com.google.android.apps.common.testing.ui.espresso.action.ViewActions;
import com.google.common.collect.Iterables;
import org.apache.commons.lang3.StringUtils;
import java.util.Collection;
import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity_;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.doesNotExist;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.RootMatchers.withDecorView;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDescendantOfA;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.*;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static org.hamcrest.CoreMatchers.not;
import static org.hamcrest.core.AllOf.allOf;
import static org.hamcrest.core.Is.is;
import static ru.crystals.vaverjanov.dreamkas.espresso.EspressoExtends.waitKeyboard;
import static ru.crystals.vaverjanov.dreamkas.espresso.EspressoExtends.withResourceName;


public class LoginActivityInstrumentationTest extends ActivityInstrumentationTestCase2<LoginActivity_>
{
    private LoginActivity mStartActivity;

    @SuppressWarnings("deprecation")
    public LoginActivityInstrumentationTest()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }

    protected void setUp() throws Exception
    {
        super.setUp();

        mStartActivity = getActivity();

        if(mStartActivity.authRequestListener == null)
        {
            mStartActivity.init(new AuthRequestIdlingResource(mStartActivity));

            //register idling resource for wait async operation
            Espresso.registerIdlingResources((AuthRequestIdlingResource)mStartActivity.authRequestListener);
        }
    }

    public void test_emptyCredentials_Login() throws Exception
    {
        //enter empty credentials
        enterCredentialsAndClick("", "");

        //don't know how to check error icon visibility inside edittext when icon is system default
        //todo make it when got style for app from uix

        //onView(withResourceName("android:icon@drawable/ic_error")).check(matches(isDisplayed()));
        //onView(allOf(withParent(withId(R.id.txtUsername)), withText(R.string.error_empty_field))).check(matches(isDisplayed()));
        //onView(allOf(withParent(withId(R.id.txtPassword)), withText(R.string.error_empty_field))).check(matches(isDisplayed()));
        //onView(withText(R.string.error_empty_field)).check(matches(isDisplayed()));
        //onView(withId(R.id.txtPassword)).perform(ViewActions.click(), closeSoftKeyboard());
        //waitKeyboard(200);
        //onView(withText(R.string.error_empty_field)).check(matches(isDisplayed()));

        tearDown();
    }

    public void test_wrongCredentials_Login() throws Exception
    {
        //register idling resource for wait async operation
        Espresso.registerIdlingResources((AuthRequestIdlingResource)mStartActivity.authRequestListener);

        //enter wrong credentials
        enterCredentialsAndClick("wrong_name", "wrong_password");

        //expect toast with error message
        onView(withText(R.string.error_bad_credentials)).inRoot(withDecorView(not(is(getActivity().getWindow().getDecorView())))).check(matches(isDisplayed()));

        tearDown();
    }

    public void test_rightCredentials_Login() throws Exception
    {
        //enter right credentials
        enterCredentialsAndClick("owner@lighthouse.pro", "lighthouse");

        //here we got new opened activity after login and here we should see action bar with title
        onView(allOf(isDescendantOfA(withResourceName("android:id/action_bar_container")), withText(R.string.dream_kas_title)));

        //check that btnLogin does not exist in current context
        onView(withId(R.id.btnLogin)).check(doesNotExist());

        //check if login activity is finishing
        assertTrue(mStartActivity.isFinishing());

        tearDown();
    }


    public void test_exitByBackBtn_Login() throws Exception
    {
        enterCredentialsAndClick("owner@lighthouse.pro", "lighthouse");

        //now on back button click we expected exit from app, because login activity disappeared
        pressBack();

        getInstrumentation().runOnMainSync(new Runnable()
        {
            @Override
            public void run()
            {
                Collection<Activity> activities = ActivityLifecycleMonitorRegistry.getInstance().getActivitiesInStage(Stage.RESUMED);

                //in that moment we expect that only one activity is still alive
                assertTrue(activities.size() == 1);

                //and that activity is LighthouseDemoActivity or its child
                assertTrue(Iterables.get(activities, 0) instanceof LighthouseDemoActivity);
            }
        });
        tearDown();
    }

    private void enterCredentialsAndClick(String username, String password)
    {
        Boolean isExpectedLoadingDialog = !StringUtils.isEmpty(username) && !StringUtils.isEmpty(password);

        onView(withId(R.id.txtUsername)).perform(clearText()).perform(ViewActions.typeText(username), closeSoftKeyboard());

        //hack, espresso bug here. without waiting cause exception
        waitKeyboard(200);

        onView(withId(R.id.txtPassword)).perform(clearText()).perform(typeText(password), closeSoftKeyboard());

        //hack, espresso bug here. without waiting cause exception
        waitKeyboard(200);

        //click on login button
        onView(withId(R.id.btnLogin)).perform(click());

        if(isExpectedLoadingDialog)
        {
            //check if loading dialog is displayed
            //onView(withText(R.string.auth_dialog_title)).check(matches(isDisplayed()));

            //check that loading dialog is dismissed
            onView(withText(R.string.auth_dialog_title)).check(doesNotExist());
        }
    }
}