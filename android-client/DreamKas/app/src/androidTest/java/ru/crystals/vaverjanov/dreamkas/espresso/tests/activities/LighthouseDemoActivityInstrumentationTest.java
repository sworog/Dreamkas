package ru.crystals.vaverjanov.dreamkas.espresso.tests.activities;

import android.test.ActivityInstrumentationTestCase2;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.ScreenshotFailureHandler;
import ru.crystals.vaverjanov.dreamkas.model.DrawerMenu;
import ru.crystals.vaverjanov.dreamkas.view.KasFragment;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity_;
import ru.crystals.vaverjanov.dreamkas.view.StoreFragment;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onData;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.setFailureHandler;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions.openDrawer;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isClosed;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isOpen;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.assertThat;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.Matchers.notNullValue;
import static org.hamcrest.core.AllOf.allOf;
import static org.hamcrest.core.Is.is;
import static org.hamcrest.core.IsInstanceOf.instanceOf;

public class LighthouseDemoActivityInstrumentationTest extends ActivityInstrumentationTestCase2<LighthouseDemoActivity_> {

    private LighthouseDemoActivity mStartActivity;

    @SuppressWarnings("deprecation")
    public LighthouseDemoActivityInstrumentationTest() {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LighthouseDemoActivity_.class);
    }

    @Override
    protected void setUp() throws Exception {
        super.setUp();
        PreferencesManager.initializeInstance(getInstrumentation().getContext());
        PreferencesManager preferences = PreferencesManager.getInstance();
        preferences.clear();
        preferences.setCurrentStore("test_store");
        mStartActivity = getActivity();
        setFailureHandler(new ScreenshotFailureHandler(getInstrumentation().getTargetContext(), mStartActivity));
    }

    @Override
    protected void tearDown() throws Exception {
        super.tearDown();
    }

    public void testUserWillSeeSelectStoresFragmentIfItIsFirstAppLaunch() throws Exception {
        tearDown();
        PreferencesManager preferences = PreferencesManager.getInstance();
        preferences.clear();
        mStartActivity = getActivity();

        onView(withText(R.string.load_stores)).check(matches(isDisplayed()));

        StoreFragment storeFragment = (StoreFragment) mStartActivity.getFragmentManager().findFragmentByTag(String.valueOf(DrawerMenu.AppStates.Store.getValue()));

        assertThat("StoreFragment does't exists in current activity", storeFragment, notNullValue());
        assertThat("StoreFragment should be visible", storeFragment.isVisible(), is(true));
    }

    public void testUserWillSeeKasFragmentIfItIsNotFirstAppLaunch() {
        mStartActivity = getActivity();

        KasFragment kasFragment = (KasFragment) mStartActivity.getFragmentManager().findFragmentByTag(String.valueOf(DrawerMenu.AppStates.Kas.getValue()));

        assertThat("KasFragment does't exists in current activity", kasFragment, notNullValue());
        assertThat("KasFragment should be visible", kasFragment.isVisible(), is(true));
    }

    public void testUserWillSeeSelectStoreFragmentIfClickOnDrawerStoreItem() {
        openDrawer(R.id.drawer_layout);

        onView(withId(R.id.drawer_layout)).check(matches(isOpen()));

        onData(allOf(is(instanceOf(String.class)), is(DrawerMenu.getMenuItems().get(DrawerMenu.AppStates.Store)))).inAdapterView(withId(R.id.lstDrawer)).perform(click());

        StoreFragment storeFragment = (StoreFragment) mStartActivity.getFragmentManager().findFragmentByTag(String.valueOf(DrawerMenu.AppStates.Store.getValue()));
        assertThat("StoreFragment does't exists in current activity", storeFragment, notNullValue());
        assertThat("StoreFragment should be visible", storeFragment.isVisible(), is(true));
    }

    public void testUserWillSeeOpenedDrawerWhenClickOnActionbarAppIcon() {
        // Drawer should not be open to start.
        onView(withId(R.id.drawer_layout)).check(matches(isClosed()));

        onView(withText(R.string.dream_kas_title)).perform(click());

        // The drawer should now be open.
        onView(withId(R.id.drawer_layout)).check(matches(isOpen()));

        onView(withText(R.string.drawer_title)).perform(click());

        // Drawer should be closed again.
        onView(withId(R.id.drawer_layout)).check(matches(isClosed()));
    }
}