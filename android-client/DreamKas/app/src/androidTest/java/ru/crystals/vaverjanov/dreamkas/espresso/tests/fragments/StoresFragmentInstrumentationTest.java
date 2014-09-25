package ru.crystals.vaverjanov.dreamkas.espresso.tests.fragments;

import android.test.ActivityInstrumentationTestCase2;

import com.google.android.apps.common.testing.ui.espresso.Espresso;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.FragmentHelper;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.PreconditionHelper;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.RequestIdlingResource;
import ru.crystals.vaverjanov.dreamkas.model.DrawerMenu;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity_;
import ru.crystals.vaverjanov.dreamkas.view.StoreFragment;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onData;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerActions.openDrawer;
import static com.google.android.apps.common.testing.ui.espresso.contrib.DrawerMatchers.isOpen;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.assertThat;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.Matchers.greaterThan;
import static org.hamcrest.core.AllOf.allOf;
import static org.hamcrest.core.Is.is;
import static org.hamcrest.core.IsInstanceOf.instanceOf;

public class StoresFragmentInstrumentationTest extends ActivityInstrumentationTestCase2<LighthouseDemoActivity_> {

    private LighthouseDemoActivity mStartActivity;
    private StoreFragment mStoreFragment;

    @SuppressWarnings("deprecation")
    public StoresFragmentInstrumentationTest() {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LighthouseDemoActivity_.class);
    }

    @Override
    protected void setUp() throws Exception {
        super.setUp();

        //Clear preferences and login
        PreconditionHelper ph = new PreconditionHelper();
        ph.clearPreference(getInstrumentation().getContext());
        setActivityIntent(ph.login(getInstrumentation().getContext()));

        //get activity
        mStartActivity = getActivity();

        //get current fragment and register storesRequestListener
        registerStoresRequestListener();

        //wait fragment transactions
        waitFragmentTransactionEnds();
    }

    private void registerStoresRequestListener() {

        //get current fragment
        mStoreFragment = (StoreFragment) mStartActivity.getCurrentFragment();

        //register storesRequestListener idlingResource
        Espresso.registerIdlingResources(new RequestIdlingResource(mStoreFragment.storesRequestListener));
    }

    private void waitFragmentTransactionEnds() throws InterruptedException {

        //wait fragment transactions
        new FragmentHelper(mStartActivity).waitFragmentTransactionEnds();
    }

    @Override
    protected void tearDown() throws Exception {
        super.tearDown();
    }

    public void testUserWillSeeKasFragmentWithSelectedStoreWhenChooseItInSpinner() throws Exception {

        //wait for load stores
        //and then click on stores spinner
        onView(withId(R.id.spStores)).perform(click());

        //check that spinner not empty
        assertThat(
                "Haven't any stores",
                mStoreFragment.spStores.getAdapter().getCount(), greaterThan(0));

        NamedObject item = (NamedObject) mStoreFragment.spStores.getItemAtPosition(0);

        //click on first item in spinner
        onView(withText(item.getName())).perform(click());

        //click on save button
        onView(withId(R.id.btnSaveStoreSettings)).perform(click());

        //user will see kas fragment with storeId text on it
        onView(withId(R.id.lblStore)).check(matches(withText(item.getId())));
    }

    public void testUserWillSeeKasFragmentWithSelectedStoreWhenUserHaveStoreAndSelectsNewStoreFromSpinner() throws InterruptedException {

        //wait for load stores
        //and then click on stores spinner
        onView(withId(R.id.spStores)).perform(click());

        //check that spinner not empty
        assertThat(
                "Haven't any stores",
                mStoreFragment.spStores.getAdapter().getCount(), greaterThan(0));

        NamedObject item = (NamedObject) mStoreFragment.spStores.getItemAtPosition(0);

        //click on first item in spinner
        onView(withText(item.getName())).perform(click());

        //click on save button
        onView(withId(R.id.btnSaveStoreSettings)).perform(click());

        //user will see kas fragment with storeId text on it
        onView(withId(R.id.lblStore)).check(matches(withText(item.getId())));

        openDrawer(R.id.drawer_layout);

        onView(withId(R.id.drawer_layout)).check(matches(isOpen()));

        onData(allOf(is(instanceOf(String.class)), is(DrawerMenu.getMenuItems().get(DrawerMenu.AppStates.Store)))).inAdapterView(withId(R.id.lstDrawer)).perform(click());

        //get current fragment and register storesRequestListener
        registerStoresRequestListener();

        //wait fragment transactions
        waitFragmentTransactionEnds();

        //click on stores spinner
        onView(withId(R.id.spStores)).perform(click());

        NamedObject item2 = (NamedObject) mStoreFragment.spStores.getItemAtPosition(1);

        //user click the second item in spinner
        onView(withText(item2.getName())).perform(click());

        //click on save button
        onView(withId(R.id.btnSaveStoreSettings)).perform(click());

        //user will see kas fragment with storeId text on it
        onView(withId(R.id.lblStore)).check(matches(withText(item2.getId())));
    }
}