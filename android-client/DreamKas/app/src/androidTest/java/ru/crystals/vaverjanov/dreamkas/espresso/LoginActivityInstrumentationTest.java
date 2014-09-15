package ru.crystals.vaverjanov.dreamkas.espresso;

import android.app.ActivityManager;
import android.test.ActivityInstrumentationTestCase2;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.google.android.apps.common.testing.testrunner.ActivityLifecycleMonitorRegistry;
import com.google.android.apps.common.testing.ui.espresso.Espresso;
import com.google.android.apps.common.testing.ui.espresso.action.ViewActions;

import org.androidannotations.annotations.ViewById;
import org.hamcrest.Description;
import org.hamcrest.Matcher;
import org.hamcrest.TypeSafeMatcher;

import java.util.List;

import ru.crystals.vaverjanov.dreamkas.BuildConfig;
import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity_;

import static android.test.ViewAsserts.assertOnScreen;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.doesNotExist;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDescendantOfA;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.*;
/*
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions.closeDrawer;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions.openDrawer;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isClosed;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isOpen;
import com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions;
*/
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static org.hamcrest.core.AllOf.allOf;
import static org.hamcrest.core.Is.is;


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
        onView(withId(R.id.txtPassword)).perform(clearText()).perform(typeText("lighthouse"));
        onView(withId(R.id.btnLogin)).perform(click());
        Espresso.registerIdlingResources(mStartActivity.authRequestListener);
        //onView(allOf(isDescendantOfA(withResourceName("android:id/action_bar_title")), withText("DreamKas"))).check(matches(isDisplayed()));
        //onView(allOf(isDescendantOfA(withId(R.id.action_settings), withText("DreamKas"))).check(matches(isDisplayed())));
        //onView(withId(R.id.action_settings)).check(matches(withText(("DreamKas"))));

        assertFalse(mStartActivity.isActive);

        //onView(withId(getActivity().getResources().getIdentifier("action_bar_title", "id", "android"))).check(matches(withText(("DreamKas"))));
    }

    public static Matcher<View> withResourceName(String resourceName)
    {
        return withResourceName(is(resourceName));
    }

    public static Matcher<View> withResourceName(final Matcher<String> resourceNameMatcher)
    {
        return new TypeSafeMatcher<View>()
        {
            @Override
            public void describeTo(Description description)
            {
                description.appendText("with resource name: ");
                resourceNameMatcher.describeTo(description);
            }

            @Override
            public boolean matchesSafely(View view)
            {
                int id = view.getId();
                return id != View.NO_ID && id != 0 && view.getResources() != null
                        && resourceNameMatcher.matches(view.getResources().getResourceName(id));
            }
        };
    }

    /*
    public void testOpenAndCloseDrawer()
    {
        // Drawer should not be open to start.
        onView(withId(R.id.drawer_layout)).check(matches(isClosed()));
        openDrawer(R.id.drawer_layout);
        // The drawer should now be open.
        onView(withId(R.id.drawer_layout)).check(matches(isOpen()));
        closeDrawer(R.id.drawer_layout);
        // Drawer should be closed again.
        onView(withId(R.id.drawer_layout)).check(matches(isClosed()));
    }

    public void testDrawerOpenAndClick()
    {
        openDrawer(R.id.drawer_layout);
        onView(withId(R.id.drawer_layout)).check(matches(isOpen()));

        // Click an item in the drawer. We use onData because the drawer is backed by a ListView, and
        // the item may not necessarily be visible in the view hierarchy.
        int rowIndex = 2;
        String rowContents = DrawerActivity.DRAWER_CONTENTS[rowIndex];
        onData(allOf(is(instanceOf(String.class)), is(rowContents))).perform(click());

        // clicking the item should close the drawer.
        onView(withId(R.id.drawer_layout)).check(matches(isClosed()));

        // The text view will now display "You picked: Pickle"
        onView(withId(R.id.drawer_text_view)).check(matches(withText("You picked: " + rowContents)));
    }

    public void testLogoff()
    {

        ActivityManager mngr = (ActivityManager) getSystemService( ACTIVITY_SERVICE );
        List<ActivityManager.RunningTaskInfo> taskList = mngr.getRunningTasks(10);
        if(taskList.get(0).numActivities == 1 && taskList.get(0).topActivity.getClassName().equals(this.getClass().getName()))
        {

        }
    }*/
}