package ru.crystals.vaverjanov.dreamkas.espresso;

import android.test.ActivityInstrumentationTestCase2;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.google.android.apps.common.testing.testrunner.ActivityLifecycleMonitorRegistry;
import com.google.android.apps.common.testing.ui.espresso.Espresso;
import com.google.android.apps.common.testing.ui.espresso.action.ViewActions;

import org.androidannotations.annotations.ViewById;

import ru.crystals.vaverjanov.dreamkas.BuildConfig;
import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity_;

import static android.test.ViewAsserts.assertOnScreen;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.doesNotExist;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.*;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;


public class LoginActivityInstrumentationTest extends ActivityInstrumentationTestCase2<LoginActivity_> {
    private LoginActivity mStartActivity;

    public LoginActivityInstrumentationTest()
    {
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }

    protected void setUp() throws Exception
    {
        super.setUp();
        mStartActivity = getActivity();
    }

    public void testLogin()
    {
        onView(withId(R.id.txtUsername)).perform(clearText()).perform(ViewActions.typeText("owner@lighthouse.pro"));
        onView(withId(R.id.txtPassword)).perform(clearText()).perform(scrollTo(), typeText("lighthouse"));
        onView(withId(R.id.btnLogin)).perform(scrollTo(), click());
        Espresso.registerIdlingResources(mStartActivity.authRequestListener);

        onView(withId(R.id.lblWelcome)).check(matches(withText(("Success"))));
    }
}