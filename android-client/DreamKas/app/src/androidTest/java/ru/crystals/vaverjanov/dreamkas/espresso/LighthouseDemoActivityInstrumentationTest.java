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
import ru.crystals.vaverjanov.dreamkas.model.DreamkasFragments;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity_;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity;
import ru.crystals.vaverjanov.dreamkas.view.LoginActivity_;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onData;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.clearText;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.closeSoftKeyboard;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.pressBack;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.typeText;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.doesNotExist;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions.closeDrawer;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions.openDrawer;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isClosed;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isOpen;
import static com.google.android.apps.common.testing.ui.espresso.matcher.RootMatchers.withDecorView;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDescendantOfA;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.CoreMatchers.not;
import static org.hamcrest.core.AllOf.allOf;
import static org.hamcrest.core.Is.is;
import static org.hamcrest.core.IsInstanceOf.instanceOf;
import static ru.crystals.vaverjanov.dreamkas.espresso.EspressoExtends.waitKeyboard;
import static ru.crystals.vaverjanov.dreamkas.espresso.EspressoExtends.withResourceName;

/*
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions.closeDrawer;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions.openDrawer;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isClosed;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isOpen;
import com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions;
*/


public class LighthouseDemoActivityInstrumentationTest extends ActivityInstrumentationTestCase2<LighthouseDemoActivity_>
{
    private LighthouseDemoActivity mStartActivity;

    public LighthouseDemoActivityInstrumentationTest()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LighthouseDemoActivity_.class);
    }

    protected void setUp() throws Exception
    {
        super.setUp();
        mStartActivity = getActivity();
    }

   /* public void testOpenAndCloseDrawer()
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

    @SuppressWarnings("unchecked")
    public void testDrawerOpenAndClick() {
        openDrawer(R.id.drawer_layout);

        onView(withId(R.id.drawer_layout)).check(matches(isOpen()));

        // Click an item in the drawer. We use onData because the drawer is backed by a ListView, and
        // the item may not necessarily be visible in the view hierarchy.
        String rowContents = mStartActivity.getResources().getStringArray(R.array.views_array)[DreamkasFragments.Exit.getValue()];
        onData(allOf(is(instanceOf(String.class)), is(rowContents))).perform(click());

        // clicking the item should close the drawer
        onView(withId(R.id.drawer_layout)).check(matches(isClosed()));

        //and show exit dialog.
        onView(withText(R.string.exitDialogMsg)).check(matches(isDisplayed()));

        //click "No"
        onView(withText(R.string.No)).perform(click());

        //exit dialog dismiss
        onView(withText(R.string.exitDialogMsg)).check(matches(not(isDisplayed())));

        // The text view will now display "You picked: Pickle"
        //onView(withId(R.id.drawer_text_view)).check(matches(withText("You picked: " + rowContents)));
    }*/
}